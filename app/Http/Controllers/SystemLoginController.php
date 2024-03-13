<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\SystemLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SystemLoginController extends Controller
{
    public function index()
    {
//        if (empty(Session::get('store_id'))){
//            return redirect()->route('shop.index')->with('error','Please select a shop first!');
//        }
        if (\request()->ajax()){
            $system_logins = SystemLogin::with('ip_address')->get();
            $data = array();
            foreach ($system_logins as $login){

                if($login->login_status == '0')
                    $status = 'Un-Successful';
                if($login->login_status == '1')
                    $status = 'Successful';
                if($login->login_status == '2')
                    $status = 'Blocked IP';
                $temp = [
                    'responsive_id'=>"",
                    'ip_address'=>$login->ip_address->ip_address,
                    'login_date'=>date("d M Y h:i a",strtotime($login->created_at)),
                    'login_email'=>$login->user_login_email,
                    'login_pass'=>$login->user_login_pass_hash,
                    'referer_url'=>$login->referer_url,
                    'session_id'=>$login->user_session_id,
                    'login_status'=>$status,
                    'user_agent'=>$login->user_agent,
                    'remarks'=>$login->quick_logout_reason
//                    'items'=>$login->items_count,
//                    'delivery_method'=>$login->delivery_method,
//                    'tags'=>""
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.ip_module.logins_list');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(SystemLogin $systemLogin)
    {
        //
    }

    public function edit(SystemLogin $systemLogin)
    {
        //
    }

    public function update(Request $request, SystemLogin $systemLogin)
    {
        //
    }

    public function destroy(SystemLogin $systemLogin)
    {
        //
    }
}
