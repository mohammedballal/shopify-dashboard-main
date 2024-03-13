<?php

namespace App\Http\Controllers;

use App\Models\CostTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CostTariffController extends Controller
{

    public function index()
    {
//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        if (\request()->ajax()){
            $shops = CostTariff::all();
            $data = array();
            // parsing the shops data to table format of json
            foreach ($shops as $shop){
                $temp = [
                    'responsive_id'=>"",
                    'id'=> $shop->id,
                    'name' => $shop->name,
                    'value' => ($shop->value),
                    'frequency' => ucwords(str_replace("_"," ",$shop->frequency)),
                    'total' => $shop->total
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.cost_tariffs.list');
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'max:255'],
            'total' => ['required', 'max:255'],
        ]);
        $inputs = $request->except(['_token']);
        $inputs['created_at'] = $inputs['updated_at'] = now();
        CostTariff::create($inputs);
        return redirect()->back()->with('success','Record Successfully created.');
    }

    public function show(Shop $shop)
    {
        //
    }

    public function edit($id)
    {
        $shop = CostTariff::findorfail($id);

        return Response::json([
            'id'=> $shop->id,
            'name' => $shop->name,
            'value' => ($shop->value),
            'frequency' => ($shop->frequency),
            'total' => $shop->total
        ],200);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'max:255'],
            'total' => ['required', 'max:255'],
        ]);
        $inputs = $request->except(['_token']);
        $inputs['updated_at'] = now();
        CostTariff::where('id',$id)->update($inputs);
        return redirect()->back()->with('success','CostTariff '.$request->name.' Successfully updated.');
    }

    public function destroy($id)
    {
//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        $cost = CostTariff::findorfail($id);
        if ($cost->delete()){
            return redirect()->back()->with("success","Record deleted successfully");
        }

        return redirect()->back()->with("error","Something went wrong");
    }
}
