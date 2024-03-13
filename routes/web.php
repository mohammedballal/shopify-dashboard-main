<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostTariffController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IpAddressController;
use App\Http\Controllers\PriceTableController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SystemLoginController;
use Edbizarro\LaravelFacebookAds\Facades\FacebookAds;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdsInsightsFields;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use Laravel\Socialite\Facades\Socialite;
use FacebookAds\Object\AdsInsights;
use Automattic\WooCommerce\Client;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    dd(app()->getLocale());
//    \Illuminate\Support\Facades\Artisan::call("import:orders casdasdasd");
    return redirect(\route("login"));
});
Route::get('privacy/policy', function () {
    return view('template.privacy_policy');
});
Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    App::setLocale($locale);
    return redirect()->back();
});
//    Route::get('/users', function () {
//        return view('template.user.list');
//    })->name('users.list');
//    Route::get('/profile', function () {
//        $user = auth()->user();
//        return view('template.layouts.views.profile',compact('user'));
//    })->name('user.profile');

Route::middleware(['auth','2fa'])->group( function(){
    Route::get('dashboard',[ProfileController::class,'dashboard'])->name('dashboard');
    Route::get('users',[RegisteredUserController::class,'list'])->name('users.list');
    Route::get('users/show',[RegisteredUserController::class,'show'])->name('users.show');
    Route::get('users/edit',[RegisteredUserController::class,'edit'])->name('users.edit');
    Route::get('user/edit/profile',[RegisteredUserController::class,'profile'])->name('user.profile.edit');
    Route::post('user/store/profile',[RegisteredUserController::class,'profile_update'])->name('users.profile.store');
    Route::put('users/update',[RegisteredUserController::class,'update'])->name('users.update');
    Route::get('users/destroy',[RegisteredUserController::class,'destroy'])->name('users.destroy');
    Route::post('user/store',[RegisteredUserController::class,'store'])->name('user.store');
    Route::get('user/account',[ProfileController::class,'account'])->name('user.account');
    Route::get('user/profile',[ProfileController::class,'index'])->name('profile.index');
    Route::get('/account/security',[RegisteredUserController::class,'security'])->name('user.security');
    Route::post('/account/store/security',[RegisteredUserController::class,'securityStore'])->name('user.store.security');
    Route::get('orders/list',[OrderController::class,'list'])->name('orders.list');
    Route::get('orders/list/wp',[OrderController::class,'list_wp'])->name('orders.list.wp');
    Route::get('invoices/list',[OrderController::class,'list'])->name('orders.invoices_list');
    Route::get('invoices/list/wp',[OrderController::class,'list_wp'])->name('orders.invoices_list_wp');
    Route::get('orders/invoice/{id}',[OrderController::class,'order_invoice'])->name('order.invoice');
    Route::get('orders/invoice/wp/{id}',[OrderController::class,'order_invoice_wp'])->name('order.invoice.wp');
    Route::get('orders/show/{id}',[OrderController::class,'order_invoice'])->name('order.show');
    Route::get('orders/show/wp/{id}',[OrderController::class,'order_invoice_wp'])->name('order.show.wp');
    Route::get('orders/invoice/{id}/print',[OrderController::class,'order_invoice_print'])->name('order.invoice.print');
    Route::get('orders/invoice/{id}/download',[OrderController::class,'order_invoice_download'])->name('order.invoice.download');
    Route::post('orders/invoice/{id}/send',[OrderController::class,'order_invoice_send'])->name('order.invoice.send');
    Route::post('orders/invoices/print',[OrderController::class,'order_invoices_print'])->name('order.invoices.print');
// WP Routes
    Route::get('orders/invoice/{id}/print/wp',[OrderController::class,'order_invoice_print_wp'])->name('order.invoice.print.wp');
    Route::get('orders/invoice/{id}/download/wp',[OrderController::class,'order_invoice_download_wp'])->name('order.invoice.download.wp');
    Route::post('orders/invoice/{id}/send/wp',[OrderController::class,'order_invoice_send_wp'])->name('order.invoice.send.wp');
    Route::post('orders/invoices/print/wp',[OrderController::class,'order_invoices_print_wp'])->name('order.invoices.print.wp');

    Route::get('shops',[ShopController::class,'index'])->name('shop.index');
    Route::get('shop/edit/{id}',[ShopController::class,'edit'])->name('shop.edit');
    Route::post('shop/update/{id}',[ShopController::class,'update'])->name('shop.update');
    Route::post('shop/store',[ShopController::class,'store'])->name('shop.store');
    Route::get('shop/destroy',[ShopController::class,'destroy'])->name('shop.destroy');
    Route::get('change/shop/{id}',[ShopController::class,'changeShop'])->name('shop.change');
    Route::get('update/layout',[RegisteredUserController::class,'updateLayout']);
    Route::resource('ticket',TicketController::class);
    Route::resource('event',EventController::class)->except('show');
    Route::resource('costtariff',CostTariffController::class)->except(['show','destroy','update']);
    Route::get('costtariff/destroy/{id}',[CostTariffController::class,'destroy']);
    Route::post('costtariff/update/{id}',[CostTariffController::class,'update']);
    Route::resource('category',CategoryController::class)->except(['show','destroy','update']);
    Route::get('category/destroy/{id}',[CategoryController::class,'destroy']);
    Route::post('category/update/{id}',[CategoryController::class,'update']);
    Route::resource('expense',ExpenseController::class)->except(['destroy','update']);
    Route::get('expense/destroy/{id}',[ExpenseController::class,'destroy']);
    Route::post('expense/update/{id}',[ExpenseController::class,'update']);

    Route::resource('price',PriceTableController::class)->except(['destroy','update']);
    Route::get('price/destroy/{id}',[PriceTableController::class,'destroy']);
    Route::post('price/update/{id}',[PriceTableController::class,'update']);

    Route::get('events',[EventController::class,'list'])->name('events.list');
    Route::post('event/label',[EventController::class,'labelStore'])->name('label.store');
    Route::get('event/label/{id}/delete',[EventController::class,'labelDelete'])->name('label.delete');
    Route::get('ticket/{id}/update/{status}/status',[TicketController::class,'updateStatus'])->name('ticket.updateStatus');

    // IP Module Routes
    Route::get('ip/list',[IpAddressController::class,'index'])->name('ip.list');
    Route::get('logins/list',[SystemLoginController::class,'index'])->name('logins.list');
    Route::get('campaigns/list',[CampaignsController::class,'index'])->name('campaigns.list');
    Route::get('campaigns/list/api',[CampaignsController::class,'campaigns_api'])->name('campaigns.api');

});
//Route::get('install',[OrderController::class,'api']);
////Route::get('install',[OrderController::class,'generate_token']);
//Route::get('generate_token',[OrderController::class,'generate_token']);
require __DIR__.'/auth.php';

//Route::get("/sdk-b",function (){
//
//
//    $app_id = "506277567581270";
//    $app_secret = "e153fc571d5c5443686346184abe7645";
//    $access_token = env("FACEBOOK_ACCESS_TOKEN");
//    $account_id = "act_348814429";
//
//    Api::init($app_id, $app_secret, $access_token);
//// The Api object is now available trough singleton
//    $api = Api::instance();
//    $api->setDefaultGraphVersion("13.0");
//    $account = new AdAccount($account_id);
//    $cursor = $account->getCampaigns();
//dd($cursor);
//// Loop over objects
//    foreach ($cursor as $campaign) {
//        echo $campaign->{CampaignFields::NAME}.PHP_EOL;
//    }
//});
//
Route::get('/sdk',function (){
    $access_token = 'EAAKCB7c5B30BAPATHj3Y8n28bAFu6ZBWZCTLsvTAvxVqMqZCUYVYwqXbHJBAQBZAHor4xaZCjx9W5L2rJ8CGf4KRQJE57LDQLZBITAKQq75GHlMZCBIWZBn9ZCshgUTmsmZCqxjXZAjfmNv2OQol0Ai0gYLB01tsQZCcxA4sLF32qFZCHF5jdFiAUOZCpOkHBTmVUigZCKBHFE3yx45EwKOFR3E0DjgSwZBhBOYpyY4TCXmj0pf3TASI9C40ZCUyKHHLPwfZACqJIZD';
    $adsApi =FacebookAds::init($access_token);
    $adAccounts = $adsApi->adAccounts()->all();
//    dd($adAccounts);
//    $ads = FacebookAds::campaigns()->all(
//        [
//          "account_id",
//          "adlabels",
//          "bid_strategy",
//          "boosted_object_id",
//          "brand_lift_studies",
//          "budget_rebalance_flag",
//          "budget_remaining",
//          "buying_type",
//          "can_create_brand_lift_study",
//          "can_use_spend_cap",
//          "configured_status",
//          "created_time",
//          "daily_budget",
//          "effective_status",
//          "id", "6267784068614",
//          "issues_info",
//          "last_budget_toggling_time",
//          "lifetime_budget",
//          "name",
//          "objective",
//          "pacing_type",
//          "promoted_object",
//          "recommendations",
//          "source_campaign",
//          "source_campaign_id",
//          "spend_cap",
//          "start_time",
//          "status",
//          "stop_time",
//          "topline_id",
//          "updated_time",
//          "adbatch",
//          "execution_options",
//          "iterative_split_test_configs",
//          "upstream_events",
//            ]
//        ,"act_348814429");
//    $ads = FacebookAds::campaigns()->all(array("ad_strategy_id"),"296584280517890");

//    $ads = FacebookAds::adAccounts()->all();
//    $ads = FacebookAds::adAccounts()->all()->map(function ($adAccount) {
//        return $adAccount->ads(
//            [
//                'name',
//                'account_id',
//                'account_status',
//                'balance',
//                'campaign',
//                'campaign_id',
//                'status'
//            ]
//        );
//    });
    dd($adsApi->campaigns()->all(
        [
            "account_id",
            "adlabels",
            "bid_strategy",
            "boosted_object_id",
            "brand_lift_studies",
            "budget_rebalance_flag",
            "budget_remaining",
            "buying_type",
            "can_create_brand_lift_study",
            "can_use_spend_cap",
            "configured_status",
            "created_time",
            "daily_budget",
            "effective_status",
            "id", "6267784068614",
            "issues_info",
            "last_budget_toggling_time",
            "lifetime_budget",
            "name",
            "objective",
            "pacing_type",
            "promoted_object",
            "recommendations",
            "source_campaign",
            "source_campaign_id",
            "spend_cap",
            "start_time",
            "status",
            "stop_time",
            "topline_id",
            "updated_time",
            "adbatch",
            "execution_options",
            "iterative_split_test_configs",
            "upstream_events",
        ]
        ,"act_348814429"));
});
//
//Route::get('/fb',function (){
//    return Socialite::driver('facebook')->redirect();
////    $fb = new Facebook\Facebook([
////        'app_id' => '{app-id}',
////        'app_secret' => '{app-secret}',
////        'default_graph_version' => 'v2.10',
////    ]);
////
////    $helper = $fb->getRedirectLoginHelper();
////
////    $permissions = ['email']; // Optional permissions
////    $loginUrl = $helper->getLoginUrl('http1://127.0.0.1:8000/fb-callback', $permissions);
////
////    echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
//});
//Route::get("fb-callback",function (){
//   dd(request());
//});
//Route::get('privacy',function (){
//    return dd("asdasd");
//});
Route::get("/fb/login",[AuthenticatedSessionController::class,'facebook_login'])->name('fb.login');
Route::get("/fb/callback",[AuthenticatedSessionController::class,'facebook_callback'])->name('facebook_callback');
Route::get('/test',function (){
    $access_token = 'EAAKCB7c5B30BAICqrr4PmnozpsktwGpx2yK73tp6jyXhMzcKyt47JOsQuGgkxjZCGAK088SKKRM3pZCKCbCyUtpPo8f6XIhd111Y2NvwHmwdpGJKj3F4n37ZBTzzBNcWsOUQ9nswG9lgFwF0dVUvuafKyb9wyfEVFogGYR17BxHfvYKyU1Y6vgbZBQIZBSM5wZBsxFSJ2GFsx7uRpDV7u3WEfkKGmKAoM2wVduF3F0iI3OPCj73brP';
    $ad_account_id = 'act_348814429';
    $app_secret = 'debc8ffce9084e576e182932102a47bb';
    $app_id = '705919603771261';

    $api = Api::init($app_id, $app_secret, $access_token);
    $api->setLogger(new CurlLogger());
    $account = new AdAccount($ad_account_id);
    $fields = array(
        CampaignFields::NAME,
        CampaignFields::ADLABELS,
        CampaignFields::OBJECTIVE,
        CampaignFields::PROMOTED_OBJECT,
        CampaignFields::ACCOUNT_ID,
        CampaignFields::DAILY_BUDGET,
        CampaignFields::BUDGET_REMAINING,
        CampaignFields::BID_STRATEGY,
        CampaignFields::SPEND_CAP,
        CampaignFields::ID,
    );

    $cursor = $account->getCampaigns($fields);
    $stats = [];
    foreach ($cursor as $single){
//        dd($single->id);
        $campaign = new Campaign($single->id);
        $params = array(
            'time_range' => array(
                'since' => (new DateTime("-1 year"))->format('Y-m-d'),
                'until' => (new DateTime('NOW'))->format('Y-m-d'),
            ),
        );
        $insight_fields = array(
            AdsInsightsFields::IMPRESSIONS,
            AdsInsightsFields::ACCOUNT_ID,
            AdsInsightsFields::OBJECTIVE,
            AdsInsightsFields::ATTRIBUTION_SETTING,
            AdsInsightsFields::AD_ID,
            AdsInsightsFields::CAMPAIGN_ID,
            AdsInsightsFields::CAMPAIGN_NAME,
            AdsInsightsFields::ACCOUNT_CURRENCY,
            AdsInsightsFields::ACCOUNT_NAME,
            AdsInsightsFields::ACTION_VALUES,
            AdsInsightsFields::REACH,
            AdsInsightsFields::AD_NAME,
            AdsInsightsFields::ACTIONS,
            AdsInsightsFields::AD_BID_VALUE,
            AdsInsightsFields::COST_PER_CONVERSION,
            AdsInsightsFields::INLINE_LINK_CLICKS,
            AdsInsightsFields::SPEND,
            AdsInsightsFields::BUYING_TYPE,
            AdsInsightsFields::CPM,
            AdsInsightsFields::ADSET_END,
            AdsInsightsFields::CPC,
            AdsInsightsFields::CPP,
            AdsInsightsFields::DDA_RESULTS,
            AdsInsightsFields::WISH_BID,
        );
        $insights = $campaign->getInsights($insight_fields, $params);
//        foreach ($insights as $ins){
//            $acc_id = $ins->{AdsInsightsFields::ACCOUNT_ID}.PHP_EOL;
//            $acc_name = $ins->{AdsInsightsFields::ACCOUNT_NAME}.PHP_EOL;
//            $acc_curr = $ins->{AdsInsightsFields::ACCOUNT_CURRENCY}.PHP_EOL;
//            $imp = $ins->{AdsInsightsFields::IMPRESSIONS}.PHP_EOL;
//            $cpc = $ins->{AdsInsightsFields::COST_PER_CONVERSION}.PHP_EOL;
//            $clicks = $ins->{AdsInsightsFields::SPEND}.PHP_EOL;
//            $buy_type = $ins->{AdsInsightsFields::BUYING_TYPE}.PHP_EOL;
//            $as = $ins->{AdsInsightsFields::ATTRIBUTION_SETTING}.PHP_EOL;
//            $DDA_RESULTS = $ins->{AdsInsightsFields::DDA_RESULTS}.PHP_EOL;
////            dd($acc_id,$acc_name,$acc_curr,$cpc,$imp,$clicks,$buy_type,$as,$DDA_RESULTS);
//        }
//        dd($insights[0]->campaign_name);
        $stats[] = $insights;
//        dd($single);
    }
    dd($stats);
//    dd($cursor);
//    $fields = array(
//        'objective',
//        'buying_type',
//        'adset_name',
//    );
//    $params = array(
//        'time_range' => array('since' => '2022-02-13','until' => '2022-03-15'),
//        'filtering' => array(array('field' => 'impressions','operator' => 'IN','value' => array('active','scheduled','pending_review','inactive','recently_rejected','rejected','not_delivering','not_published','rejected','completed','recently_completed'))),
//        'level' => 'campaign',
//        'breakdowns' => array('ad_format_asset'),
//    );
//    echo json_encode((new AdAccount($ad_account_id))->getInsights(
//        $fields,
//        $params
//    )->getResponse()->getContent(), JSON_PRETTY_PRINT);
});
Route::get("/direct",function (){
    $access_token = 'EAAKCB7c5B30BAHv3vE8cJMJVL6WjKsmZA5MsO2bQwJdA6KT5mNmZAALyWD4NHmdm1v1pscYjGcLXSm1xKPAHFxAMbsl3r7c1DJWHH50r9WNsdu8oUumWgzvzS83cbOZCk1koFGRT1I1fGFFA2ZCevMLRF5ZA5qNmZAcLH7wSleXe97Cv2tPnC5LfanFqVu6ZBsZD';
    $ad_account_id = 'act_2135220696642223';
    $app_secret = 'debc8ffce9084e576e182932102a47bb';
    $app_id = '705919603771261';

    $api = Api::init($app_id, $app_secret, $access_token);
    $api->setLogger(new CurlLogger());

    $fields = array(
        'campaign_group_id',
        'campaign_group_name',
        'delivery',
        'bid',
        'budget',
        'attribution_setting',
        'results',
        'reach',
        'impressions',
        'cost_per_result',
        'spend',
        'stop_time',
        'frequency',
        'unique_actions:link_click',
    );
    $params = array(
        'time_range' => array('since' => '2022-02-13','until' => '2022-03-15'),
        'level' => 'campaign',
    );
    echo json_encode((new AdAccount($ad_account_id))->getInsights(
        $fields,
        $params
    )->getResponse()->getContent(), JSON_PRETTY_PRINT);
});
//Route::get("wp",[OrderController::class,'fetch_wp_orders']);
//Route::get("wp",function (){
//    $woocommerce = new Client(
//        'https://lojacasa741.com.br',
//        'ck_92b5b2159edfc840771565ea70dd2cb764746a4f',
//        'cs_d8393862e8854946972c7436b2b1a5e9629b7126',
//        [
//            'version' => 'wc/v3',
//        ]
//    );
////    dd(($woocommerce->get('orders',[])));
////    $woocommerce = new Client('https://lojacasa741.com.br/', 'ck_92b5b2159edfc840771565ea70dd2cb764746a4f', 'cs_d8393862e8854946972c7436b2b1a5e9629b7126', []);
//    // Array of response results.
////    $results = $woocommerce->get('customers');
////    dd($results);
//
//    $results = $woocommerce->get('orders',['context'=>'view','status'=>'any']);
//    dd($results);
//});
