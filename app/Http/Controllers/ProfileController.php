<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller
{
    public function dashboard(){
        $chartData = array();
        if (Auth::user()->hasRole('Super Admin')){
            foreach (getShops() as $shop){
                $chartData[$shop->name] = array();
                    $orders = Order::select('order_date','total_usd')->get();
                    foreach ($orders as $order){
                        $chartData[$shop->name][date('d-M-y_H-i-s-a',strtotime($order->order_date))]=$order->toArray();
                    }
            }
        }else{
            $coupons = Auth::user()->tag_id?json_decode(Auth::user()->tag_id):array();
            foreach (getShops() as $shop){
                $chartData[$shop->name] = array();
                foreach ($coupons as $coupon) {
                    $orders = Order::select('order_date','total_usd')->where('tags','LIKE',"%$coupon%")->get();
                    foreach ($orders as $order){
                        $chartData[$shop->name][date('d-M-y_H-i-s-a',strtotime($order->order_date))]=$order->toArray();
                    }
                }
            }
        }
        return view('template.dashboard',compact('chartData'));
    }

    public function account(){
        $user = auth()->user();
        return view('template.user.account.account',compact('user'));
    }

    public function index(){
        $user = auth()->user();
        $data = $coupons = array();
        if ($user->hasRole('Super Admin')){
            $tags = Order::pluck('tags')->toarray();
            foreach ($tags as $tag){
                $t = explode(',',json_decode($tag));
                foreach ($t as $e){
                    array_push($coupons,$e);
                }
            }
            $coupons = array_unique($coupons);
            foreach ($coupons as $coupon){
                $sales = Order::where('tags','LIKE',"%$coupon%")->sum('total_usd');
                $temp = [
                    'responsive_id'=>"",
                    'coupon'=> $coupon,
                    'products'=> Order::where('tags','LIKE',"%$coupon%")->sum('items_count'),
                    'sales'=> '$'.$sales,
                    'commission'=> '$',
                    'percent'=> rand(25,100),
                ];
                array_push($data,$temp);
            }

        }else{
            $coupons = $user->tag_id?json_decode($user->tag_id):array();
            foreach ($coupons as $coupon){
                $sales = Order::where('tags','LIKE',"%$coupon%")->sum('total_usd');
                $temp = [
                    'responsive_id'=>"",
                    'coupon'=> $coupon,
                    'products'=> Order::where('tags','LIKE',"%$coupon%")->sum('items_count'),
                    'sales'=> '$'.$sales,
                    'commission'=> '$'.($user->commission/100)*$sales,
                    'percent'=> rand(25,100),
                ];
                array_push($data,$temp);
            }
        }
        return Response::json(['data'=>$data],200);
    }
}
