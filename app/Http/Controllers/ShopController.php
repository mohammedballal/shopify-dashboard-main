<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!(Auth::user()->hasRole("Super Admin")))
            abort(404);
        if (\request()->ajax()){
            $shops = Shop::all();
            $data = array();
            // parsing the shops data to table format of json
            foreach ($shops as $shop){
                $temp = [
                    'responsive_id'=>"",
                    'id'=> $shop->id,
                    'name' => $shop->name,
                    'api_key' => decrypt($shop->api_key),
                    'api_pass' => decrypt($shop->api_pass),
                    'api_version' => $shop->api_version
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.shops.list');
    }

    public function changeShop($id)
    {
        if ($id == 'all'){
            Session::forget(['store_id','store_name']);
            Session::put(['store_id'=>'all','store_name'=>'All Stores']);
            return redirect()->back()->with('success','All Stores Setup Complete');
        }
        else
            $shop = Shop::select(['name','id'])->where('id',decrypt($id))->first();
        if (@$shop){
            Session::forget(['store_id','store_name']);
            Session::put(['store_id'=>encrypt($shop->id),'store_name'=>$shop->name]);
            return redirect()->back()->with('success',''.$shop->name.' Store Setup Complete');
        }else{
            return redirect()->back()->with('error','Something went wrong please try again.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255','unique:shops'],
            'api_key' => ['required', 'string', 'max:255'],
            'api_pass' => ['required', 'string', 'max:255'],
            'api_version' => ['required', 'string', 'max:255'],
        ]);
        $inputs = $request->except(['_token']);
        $inputs['api_key'] = encrypt($request->api_key);
        $inputs['api_pass'] = encrypt($request->api_pass);
        $inputs['created_at'] = $inputs['updated_at'] = now();
        Shop::create($inputs);
        return redirect()->back()->with('success','Shop Successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = Shop::findorfail($id);

        return Response::json([
            'id'=> $shop->id,
            'name' => $shop->name,
            'api_key' => decrypt($shop->api_key),
            'api_pass' => decrypt($shop->api_pass),
            'api_version' => $shop->api_version
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('shops', 'name')->ignore($id)],
            'api_key' => ['required', 'string', 'max:255'],
            'api_pass' => ['required', 'string', 'max:255'],
            'api_version' => ['required', 'string', 'max:255'],
        ]);
        $inputs = $request->except(['_token']);
        $inputs['api_key'] = encrypt($request->api_key);
        $inputs['api_pass'] = encrypt($request->api_pass);
        $inputs['updated_at'] = now();
        Shop::where('id',$id)->update($inputs);
        return redirect()->back()->with('success','Shop '.$request->name.' Successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if (!(Auth::user()->hasRole("Super Admin")))
            abort(404);
        $shop_id = \request()->get("shop_id");
        $shop = Shop::findorfail($shop_id);
        if ($shop->delete()){
                return redirect()->back()->with("success","User deleted successfully");
        }

        return redirect()->back()->with("error","Something went wrong");
    }
}
