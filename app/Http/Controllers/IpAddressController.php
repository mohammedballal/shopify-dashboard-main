<?php

namespace App\Http\Controllers;

use App\Models\IpAddress;
use App\Models\Order;
use App\Models\Shop;
use App\Models\SystemLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IpAddressController extends Controller
{
    public function index()
    {
//        if (empty(Session::get('store_id'))){
//            return redirect()->route('shop.index')->with('error','Please select a shop first!');
//        }
        if (\request()->ajax()){
            $ips = IpAddress::all();
            $data = array();
            foreach ($ips as $ip){

                if($ip->status == '0')
                    $status = 'Blocked';
                if($ip->status == '1')
                    $status = 'Un-Blocked';
                if($ip->status == '2')
                    $status = 'Whitelist';
                $temp = [
                    'responsive_id'=>"",
                    'ip_address'=>$ip->ip_address,
                    'success_rate'=>$ip->successful_logins,
                    'failure_rate'=>$ip->un_successful_logins,
                    'ip_status'=>$status,
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.ip_module.ips_list');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(IpAddress $ipAddress)
    {
        //
    }

    public function edit(IpAddress $ipAddress)
    {
        //
    }

    public function update(Request $request, IpAddress $ipAddress)
    {
        //
    }

    public function destroy(IpAddress $ipAddress)
    {
        //
    }
}
