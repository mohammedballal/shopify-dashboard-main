<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PriceTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PriceTableController extends Controller
{
    public function index()
    {
        $app_id = 'cb17f8c470494efb9f4f204178030145';
        $oxr_url = "https://openexchangerates.org/api/latest.json?app_id=" . $app_id;

// Open CURL session:
        $ch = curl_init($oxr_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Get the data:
        $json = curl_exec($ch);
        curl_close($ch);

// Decode JSON response:
        $oxr_latest = json_decode($json);
        $string = "1 ".$oxr_latest->base." equals ".round($oxr_latest->rates->BRL,2)." BRL at ".date('H:i jS F, Y', $oxr_latest->timestamp);
        $rate = round($oxr_latest->rates->BRL,2);

//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        if (\request()->ajax()){
            $shops = PriceTable::all();
            $data = array();
            // parsing the shops data to table format of json
            foreach ($shops as $shop){
                $sale_price = $shop->cost_brl * $shop->mark_up;
                $fix_cost = round(($sale_price * 10) / 100,2);
                $fb_ads = round(($sale_price * 20) / 100,2);
                $profit = round(($sale_price - $shop->cost_brl - $fix_cost - $fb_ads),2);
                $max_cpa = round($fb_ads + $profit,2);
                $temp = [
                    'responsive_id'=>"",
                    'id'=> $shop->id,
                    'product_name' => $shop->product_name,
                    'cost_usd' => "$ ". number_format($shop->cost_usd,2),
                    'cost_brl' => "R$ ". number_format($shop->cost_brl,2),
                    'mark_up' => number_format($shop->mark_up,2),
                    'sale_price' => "R$ ". number_format($sale_price,2),
                    'fix_cost' => "R$ ". number_format($fix_cost,2),
                    'fb_ads' => "R$ ". number_format($fb_ads,2),
                    'max_cpa' => "R$ ". number_format($max_cpa,2),
                    'profit' => "R$ ". number_format($profit,2),
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.price.list',compact('string','rate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'cost_usd' => ['required'],
            'cost_brl' => ['required'],
            'mark_up' => ['required']
        ]);
        $inputs = $request->except(['_token']);
        $inputs['created_at'] = $inputs['updated_at'] = now();
        PriceTable::create($inputs);
        return redirect()->back()->with('success','Price Successfully added.');
    }


    public function edit($id)
    {
        $shop = PriceTable::findorfail($id);

        return Response::json([
            'responsive_id'=>"",
            'id'=> $shop->id,
            'product_name' => $shop->product_name,
            'cost_usd' => $shop->cost_usd,
            'cost_brl' => $shop->cost_brl,
            'mark_up' => $shop->mark_up
        ],200);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'cost_usd' => ['required'],
            'cost_brl' => ['required'],
            'mark_up' => ['required']
        ]);
        $inputs = $request->except(['_token']);
        $inputs['updated_at'] = now();
        PriceTable::where('id',$id)->update($inputs);
        return redirect()->back()->with('success','Price table Successfully updated.');
    }

    public function destroy($id)
    {
//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        $cost = PriceTable::findorfail($id);
        if ($cost->delete()){
            return redirect()->back()->with("success","Price deleted successfully");
        }

        return redirect()->back()->with("error","Something went wrong");
    }
}
