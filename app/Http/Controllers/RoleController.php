<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function store(Request $request){
        $this->validate($request,[
           'name'=>'required'
        ]);
        if (Role::create($request->except(["_token"]))){
            return redirect()->back()->with('success','Saved Successfully');
        }
        return redirect()->back()->with('error','Something went wrong!');
    }
}
