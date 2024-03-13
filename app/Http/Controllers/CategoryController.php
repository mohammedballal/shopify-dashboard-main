<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CostTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function index()
    {
//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        if (\request()->ajax()){
            $shops = Category::with("parent")->get();
            $data = array();
            // parsing the shops data to table format of json
            foreach ($shops as $shop){
                $temp = [
                    'responsive_id'=>"",
                    'id'=> $shop->id,
                    'name' => $shop->name,
                    'description' => $shop->description,
                    'parent' => $shop->parent->name ?? NULL,
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.categories.list');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);
        $inputs = $request->except(['_token']);
        $inputs['created_at'] = $inputs['updated_at'] = now();
        Category::create($inputs);
        return redirect()->back()->with('success','Category Successfully created.');
    }


    public function edit($id)
    {
        $shop = Category::findorfail($id);

        return Response::json([
            'id'=> $shop->id,
            'name' => $shop->name,
            'parent_id' => ($shop->parent_id),
            'description' => ($shop->description),
        ],200);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);
        $inputs = $request->except(['_token']);
        $inputs['updated_at'] = now();
        Category::where('id',$id)->update($inputs);
        return redirect()->back()->with('success','Category '.$request->name.' Successfully updated.');
    }

    public function destroy($id)
    {
//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        $cost = Category::findorfail($id);
        if ($cost->delete()){
            return redirect()->back()->with("success","Category deleted successfully");
        }

        return redirect()->back()->with("error","Something went wrong");
    }
}
