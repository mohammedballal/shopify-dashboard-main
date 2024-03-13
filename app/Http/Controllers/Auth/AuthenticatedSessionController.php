<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Shop;
use App\Models\ShopUser;
use App\Providers\RouteServiceProvider;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('template.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();
        // Putting all check to session when user first login to the system
        Session::forget(['store_id','store_name']);
        Session::put(['store_id'=>'all','store_name'=>'All Stores']);
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function facebook_login(){
        $uri = 'https://www.facebook.com/'.env("FACEBOOK_API").'/dialog/oauth?client_id='.env('FACEBOOK_CLIENT_ID').'&redirect_uri='.env('APP_URL').'/fb/callback/'.'&scope=ads_management,ads_read';
        return redirect($uri);
//        return Socialite::with('facebook')->scopes([
//            'ads_management','ads_read','attribution_read',
//            'business_management','catalog_management',
//            'email','leads_retrieval','page_events',
//            'pages_manage_ads','pages_manage_cta','pages_manage_instant_articles',
//            'pages_manage_engagement','pages_manage_metadata'
//        ])->redirect();
    }
    public function facebook_callback(){
          $access_token = $this->get_access_token(env('FACEBOOK_CLIENT_ID'),env('FACEBOOK_CLIENT_SECRET'),\request()->get('code'),env('APP_URL').'/fb/callback/');
          if(!@json_decode($access_token)->access_token){
              return abort(500);
          }
          $access_token = json_decode($access_token)->access_token;
          $user = \App\Models\User::find(Auth::user()->id);
          $user->fb_access_token = $access_token;
          $user->fb_access_token_created_at = now();
          $user->save();
          return redirect(env('APP_URL').'/campaigns/list');
//        dd(\request()->get('code'));
//        $user = Socialite::driver('facebook')->user();
//        dd($user);
//        // OAuth 2.0 providers...
//        $token = $user->token;
//        $refreshToken = $user->refreshToken;
//        $expiresIn = $user->expiresIn;
    }
    // Client is App Id
    // Client Secret is App secret
    // Auth Code is Authorization code obtained from here https://developers.facebook.com/docs/marketing-api/overview/authorization/
    // redirect uri is callback registered in the Fb Login FB App
    public function get_access_token($client_id,$client_secret,$auth_code,$redirect_uri){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.env("FACEBOOK_API").'/oauth/access_token?client_id='.$client_id.'&redirect_uri='.$redirect_uri.'&client_secret='.$client_secret.'&code='.$auth_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: fr=0m53M8PA2xAtb0mSM..BiMWZ-.wH.AAA.0.0.BiMYsV.AWWkW2T6dOY; sb=fmYxYtFI2zLqjjqJFUPaYMho'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
}
