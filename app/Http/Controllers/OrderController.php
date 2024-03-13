<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrdersWp;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\SendInvoice;
use Automattic\WooCommerce\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Exception;

class OrderController extends Controller
{
    public static function fetch_store_orders($store_name = null){
        if ($store_name){
            $shops = Shop::where('name',$store_name)->get();
        }else{
            $shops = Shop::all();
        }
        Log::info($shops);
        $data = array();
            if (count($shops)){
                foreach ($shops as $shop){
                    $api_key = decrypt($shop->api_key);
                    $api_pass = decrypt($shop->api_pass);
                    $store_name = $shop->name;
                    $api_version = $shop->api_version;
                    foreach (OrderController::api_hit($api_key,$api_pass,$store_name,$api_version)->orders as $order){
                        $data[] = [
                            'order_no' => $order->name,
                            'order_date' => new \DateTime($order->created_at),
                            'customer_first_name' => $order->customer->first_name,
                            'customer_last_name' => $order->customer->last_name,
                            'customer_name' => $order->customer->first_name . " " . $order->customer->last_name,
                            'store_currency' => $order->currency,
                            'shop_id' => $shop->id,
                            'user_id' => OrderController::userTagCompare($order->tags),
                            'total' => $order->total_price,
                            'total_usd' => $order->total_price_usd,
                            'payment_status' => $order->financial_status,
                            'fulfillment_status' => (($order->fulfillment_status == null) or ($order->fulfillment_status === "partial")) ? "Unfulfilled" : "fulfilled",
                            'items_count' => count($order->line_items),
                            'items_array' => json_encode($order->line_items),
                            'delivery_method' => @$order->shipping_lines[0]->code?:"" ,
                            'tags' => json_encode($order->tags),
                            'order_api_response' => json_encode($order),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($data)) {
                    Log::info("Total Orders Fetched: " . count($data));
                    $total_orders_stored = \DB::table("orders")->upsert(
                        collect($data)->reverse()->toArray(),

                        [
                            "order_no"
                        ],
                        [
                            "customer_first_name",
                            "customer_last_name",
                            "customer_name",
                            "shop_id",
                            "user_id",
                            "store_currency",
                            "total",
                            "total_usd",
                            "payment_status",
                            "fulfillment_status",
                            "items_count",
                            "items_array",
                            "delivery_method",
                            "tags",
                            "order_api_response"
                        ]);
                    if ($total_orders_stored)
                        Log::info("Orders Stored / Updated");
                    else
                        Log::info("Orders already Up to date");
                }
                return 1;
            }
            else{
                Log::info("No Shop Found");
            }
    }

    public static function fetch_wp_orders($orders){
        if (count($orders)){
            $data = [];
            foreach ($orders as $order){
                $data[] = [
                    'order_no'=>$order->id,
                    'date_created'=>$order->date_created,
                    'status'=>$order->status,
                    'customer_note'=>$order->customer_note,
                    'order_object'=>json_encode($order),
                    'total'=>$order->total,
                    'currency'=>$order->currency,
                    'total_tax'=>$order->total_tax,
                    'payment_method'=>$order->payment_method,
                    'line_items'=>(@$order->line_items && is_array($order->line_items))?count($order->line_items):NULL,
                ];
            }
            if (!empty($data)) {
                Log::info("Total Orders Fetched: " . count($data));
                $total_orders_stored = \DB::table("orders_wps")->upsert(
                    collect($data)->reverse()->toArray(),

                    [
                        "order_no"
                    ],
                    [
                        "date_created",
                        "status",
                        "customer_note",
                        "order_object",
                        "total",
                        "currency",
                        "total_tax",
                        "payment_method",
                        "line_items"
                    ]);
                if ($total_orders_stored)
                    Log::info("Orders Stored / Updated");
                else
                    Log::info("Orders already Up to date");
            }
            return 1;
        }
        else{
            Log::info("0 Orders Fetched");
        }
    }

    public function list(){
        if (empty(Session::get('store_id'))){
            return redirect()->route('shop.index')->with('error','Please select a shop first!');
        }
        if (\request()->ajax()){
            try {
                $shop_ids = (Session::get('store_id') != 'all')
                    ?
                    (Shop::find(decrypt(Session::get('store_id')))->pluck('id'))
                    :
                    ((\auth()->user()->hasRole('Super Admin'))
                        ?
                        Shop::pluck('id')
                        :
                        Auth::user()->shops->pluck('id'));
                $orders = Order::exclude(['order_api_response','created_at','updated_at'])->with("shop");
//            if (!empty($shop_ids))
                $orders = $orders->whereIn('shop_id',$shop_ids)->get();
//            else
//dd(auth()->user()->hasRole('Admin'));
                $tag_id = null;
                if (!(\auth()->user()->hasRole('Super Admin'))) {
                    $tag_id = \auth()->user()->tag_id;
                }
                else{
                    if (\request()->get("user_id")) {
                        $tag_id = User::find(\request()->get("user_id"))->tag_id;
                    }
                }
                $data = array();
                foreach ($orders as $order){
                    // checking if the order tags match any of the Current Logged In User Tags
                    if ($tag_id && !(count(array_intersect(json_decode($tag_id),explode(", ",json_decode($order->tags)))) > 0)){
                        continue;
                    }
                    $html = null;
                    foreach (explode(',',json_decode($order->tags)) as $tag){
                        $html .= '<a href="#"><span class="badge badge-light-info" title="'.$tag.'">' .$tag .'</span></a>  ';
                    }
                    if (explode('/',\request()->header('referer'))[3] == 'orders')
                        $temp = [
                            'id'=>$order->id,
                            'responsive_id'=>"",
                            'order_no'=>$order->order_no,
                            'order_date'=>date("d M Y h:i a",strtotime($order->order_date)),
                            'customer'=>$order->customer_name,
                            'store_currency'=>$order->store_currency,
                            'store_name'=>$order->shop->name,
                            'total'=>$order->total,
                            'total_usd'=>$order->total_usd,
                            'payment_status'=>$order->payment_status,
                            'fulfillment_status'=>$order->fulfillment_status,
                            'items'=>$order->items_count,
                            'delivery_method'=>$order->delivery_method,
                            'tags'=>$html
                        ];
                    else
                        $temp = [
                            'id'=>$order->id,
                            'responsive_id'=>"",
                            'order_no'=>$order->order_no,
                            'order_date'=>date("F d Y",strtotime($order->order_date)),
                            'customer'=>$order->customer_name,
                            'store_name'=>$order->shop->name,
                            'total'=>$order->total,
                            'total_usd'=>$order->total_usd,
                            'payment_status'=>$order->payment_status,
                            'fulfillment_status'=>$order->fulfillment_status,
                            'items'=>$order->items_count,
                        ];
//                dd($temp);
                    array_push($data,$temp);
                }
//                dd($data);
                return response()->json(["data"=>$data]);
            }
            catch (Exception $e){
                Log::info($e);
                return \response()->json(['data'=>"Something went wrong Please contact Server manager"]);
            }
        }
        if (((preg_match("/(orders.list)/",Route::currentRouteName()))))
            return view('template.orders.list');
        else
            return view('template.orders.invoice_list');
    }
    public function list_wp(){
        if (\request()->ajax()){
            try {
                $orders = OrdersWp::all();
                $data = array();
                foreach ($orders as $order){

                    if (explode('/',\request()->header('referer'))[3] == 'orders')
                        $temp = [
                            'id'=>$order->id,
                            'responsive_id'=>"",
                            'order_no'=>$order->order_no,
                            'order_date'=>date("d M Y h:i a",strtotime($order->date_created)),
                            'customer'=>!empty($order->customer_note)?:"N/A",
                            'store_currency'=>$order->currency,
                            'store_name'=>'',
                            'total'=>$order->total,
                            'total_usd'=>$order->total_tax,
                            'payment_status'=>!empty($order->payment_method)?:"N/A",
                            'fulfillment_status'=>$order->status,
                            'items'=>$order->line_items,
                            'delivery_method'=>'',
                            'tags'=>''
                        ];
                    else
                        $temp = [
                            'id'=>$order->id,
                            'responsive_id'=>"",
                            'order_no'=>$order->order_no,
                            'order_date'=>date("F d Y",strtotime($order->date_created)),
                            'customer'=>!empty($order->customer_note)?:"N/A",
                            'store_name'=>'',
                            'store_currency'=>$order->currency,
                            'total'=>$order->total,
                            'total_usd'=>$order->total_tax,
                            'payment_status'=>!empty($order->payment_method)?:"N/A",
                            'fulfillment_status'=>$order->status,
                            'items'=>$order->line_items,
                        ];
                    array_push($data,$temp);
                }
                return response()->json(["data"=>$data]);
            }
            catch (Exception $e){
                Log::info($e);
                return \response()->json(['data'=>"Something went wrong Please contact Server manager"]);
            }
        }
        if (((preg_match("/(orders.list.wp)/",Route::currentRouteName()))))
            return view('template.orders.list_wp');
        else
            return view('template.orders.invoice_list_wp');
    }

    public function api()
    {
        $shop = "lilo-baby";
        $api_key = "0ba47cee11574dbcb5ac87e5b577d1b8";
        $scopes = "read_orders";
        $redirect_uri = "http://127.0.0.1:8000/generate_token";

        // Build install/approval URL to redirect to
        $install_url = "https://" . $shop . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);
//        dd($install_url);
        // Redirect
        header("Location: " . $install_url);
        die();
    }
    public function generate_token()
    {
        // Set variables for our request
        $shared_secret = "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";
        $params = \request()->all(); // Retrieve all request parameters
        $hmac = \request()->get('hmac'); // Retrieve HMAC request parameter
        $params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
        ksort($params); // Sort params lexographically

        // Compute SHA256 digest
        $computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

        // Use hmac data to check that the response is from Shopify or not
        if (hash_equals($hmac, $computed_hmac)) {
            $this->access_token('0ba47cee11574dbcb5ac87e5b577d1b8',$shared_secret,$params);
        } else {
            // NOT VALIDATED – Someone is trying to be shady!
            dd('not valid');
        }
    }
    private function access_token($api_key,$shared_secret,$params)
    {
        // Set variables for our request
        $query = array(
            "client_id" => $api_key, // Your API key
            "client_secret" => $shared_secret, // Your app credentials (secret key)
            "code" => $params['code'] // Grab the access key from the URL
        );

        // Generate access token URL
        $access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";
        dd($params);
        // Configure curl client and execute request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $access_token_url);
        curl_setopt($ch, CURLOPT_POST, count($query));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        $result = curl_exec($ch);
        curl_close($ch);

        // Store the access token
        $result = json_decode($result, true);
        $access_token = $result['access_token'];
        $this->orders($access_token);
    }
    private function orders($token)
    {
        // Set variables for our request
        $shop = "codecru";
//        $token = "SWplI7gKAckAlF9QfAvv9yrI3grYsSkw";
        $query = array(
            "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
        );

        // Run API call to get all products
        $orders = shopify_call($token, $shop, "/admin/orders.json", array(), 'GET');

        // Get response
        dd($orders);
    }

    private static function api_hit($api_key,$api_pass,$store_name,$api_version){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.$api_key.':'.$api_pass.'@'.$store_name.'.myshopify.com/admin/api/'.$api_version.'/orders.json?status=any',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    private static  function userTagCompare($tags)
    {
        foreach (User::with("roles")->get() as $user){
            if ($user->hasRole("User")){
                if (count(array_intersect(json_decode($user->tag_id),explode(", ",$tags))) > 0)
                    return $user->id;
                else
                    return null;
            }
        }
    }

    public function order_invoice($id){
        $order = Order::find($id);
        $items = $this->getItems($order);
        $invoice_route = check_route('order.invoice');
        return view('template.orders.invoice',compact('items','order','invoice_route'));
    }
    public function order_invoice_wp($id){
        $order = OrdersWp::find($id);
//        dd($order,json_decode($order->order_object)->line_items);
        $items = $this->getItemsWp($order);
        $invoice_route = check_route('order.invoice');
        return view('template.orders.invoice_wp',compact('items','order','invoice_route'));
    }
    public function order_invoice_print($id){
        $order = Order::find($id);
        $items = $this->getItems($order);
        return view('template.orders.invoice_print',compact('items','order'));
    }
    public function order_invoice_print_wp($id){
        $order = OrdersWp::find($id);
        $items = $this->getItemsWp($order);
        return view('template.orders.invoice_print_wp',compact('items','order'));
    }

    public function order_invoice_download($id){
        $order = Order::find($id);
        $items = $this->getItems($order);
        $pdf = Pdf::loadView('template.orders.invoice_print',compact('items','order'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('Invoice '.$order->order_no.'.pdf');
    }

    public function order_invoice_send(Request  $request,$id){
        $order = Order::find($id);
        $items = $this->getItems($order);
        $pdf = Pdf::loadView('template.orders.invoice_print',compact('items','order'))->setOptions(['defaultFont' => 'sans-serif']);
        Storage::disk('invoices')->put($order->shop_id.'/'.$order->customer_name.'/Invoice '.$order->order_no.'.pdf',$pdf->output());
        $subject = $request->subject??'Invoice for Order '.$order->order_no;
        $message = $request->message??'Thank you for shopping with us!';
        $users = $request->to_customer?array_push($request->to,$order->customer_email):$request->to;
        Notification::route('mail',$users)->notify(new SendInvoice(array('path'=>asset('media/invoices'.$order->shop_id.'/'.$order->customer_name.'/Invoice '.$order->order_no.'.pdf'),'order_no'=>$order->order_no,'customer_name'=>$order->customer_name,'subject'=>$subject,'message'=>$message)));
        return redirect()->back()->with('success','Invoice Successfully Send.');
    }

    public function order_invoices_print(){
        $orders = Order::whereIn('id',explode(',',request()->get('order_ids')))->get();
        return view('template.orders.invoices_print',compact('orders'));
    }

    private function getItems($order): array
    {   $items = array();
        $api_response = json_decode($order->order_api_response);
        $order->discount = $api_response->total_discounts;
        $order->tax = $api_response->total_tax;
        $order->sub_total = $api_response->total_line_items_price;
        $order->customer_email = $api_response->customer->email;
        foreach (json_decode($order->items_array) as $item) {
            $items[] = [
                'name'=>$item->title,
                'variant'=>$item->variant_title,
                'rate'=>$item->price,
                'quantity'=>$item->quantity,
                'currency'=>$item->price_set->shop_money->currency_code
            ];
        }
        return $items;
    }
    private function getItemsWp($order): array
    {   $items = array();
        $api_response = json_decode($order->order_object);
        $order->tax = $api_response->total_tax;
        $order->sub_total = 0;
        $order->discount = $api_response->discount_total;
        $order->store_currency = $api_response->currency;
        $order->total = $api_response->total;
        $order->customer_note = $api_response->customer_note;
        foreach ($api_response->line_items as $item) {
            $order->sub_total+=$item->price*$item->quantity;
            $items[] = [
                'name'=>$item->name,
                'variant'=>'',
                'rate'=>$item->price,
                'quantity'=>$item->quantity,
                'currency'=>$order->currency
            ];
        }
        return $items;
    }

    public function order_invoice_download_wp($id){
        $order = OrdersWp::find($id);
        $items = $this->getItemsWp($order);
        $pdf = Pdf::loadView('template.orders.invoice_print_wp',compact('items','order'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('Invoice '.$order->order_no.'.pdf');
    }

    public function order_invoice_send_wp(Request  $request,$id){
        $order = OrdersWp::find($id);
        $items = $this->getItemsWp($order);
        $pdf = Pdf::loadView('template.orders.invoice_print_wp',compact('items','order'))->setOptions(['defaultFont' => 'sans-serif']);
        Storage::disk('invoices')->put($order->shop_id.'/'.$order->customer_name.'/Invoice '.$order->order_no.'.pdf',$pdf->output());
        $subject = $request->subject??'Invoice for Order '.$order->order_no;
        $message = $request->message??'Thank you for shopping with us!';
        $users = $request->to_customer?array_push($request->to,$order->customer_email):$request->to;
        Notification::route('mail',$users)->notify(new SendInvoice(array('path'=>asset('media/invoices'.$order->shop_id.'/'.$order->customer_name.'/Invoice '.$order->order_no.'.pdf'),'order_no'=>$order->order_no,'customer_name'=>$order->customer_name,'subject'=>$subject,'message'=>$message)));
        return redirect()->back()->with('success','Invoice Successfully Send.');
    }

    public function order_invoices_print_wp(){
        $orders = OrdersWp::whereIn('id',explode(',',request()->get('order_ids')))->get();
        return view('template.orders.invoices_print_wp',compact('orders'));
    }
}
