<?php

namespace App\Http\Controllers;

use Cassandra\Date;
use DateTime;
use Edbizarro\LaravelFacebookAds\Facades\FacebookAds;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\Fields\CampaignFields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $adAccounts = [];
        if ($user->fb_access_token){
            if (!$this->access_token_expiration()){
                $adAccounts = [];
                return view('template.campaigns.list')->with('error','Your Access Token is expired Please connect Again');
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/'.config("app.facebook_api").'/me/adaccounts?access_token='.$user->fb_access_token,
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
            foreach (json_decode($response)->data as $acc){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://graph.facebook.com/'.config("app.facebook_api").'/'.$acc->id.'?fields=name,amount_spent,currency,min_daily_budget,owner,user_tasks&access_token='.$user->fb_access_token,
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
                $adAccounts[] = [
                    'id'=>json_decode($response)->id,
                    'name'=>json_decode($response)->name,
                    'amount_spent'=>json_decode($response)->amount_spent,
                    'currency'=>json_decode($response)->currency,
                    'min_daily_budget'=>json_decode($response)->min_daily_budget,
                    'user_tasks'=>json_decode($response)->user_tasks,
                    'owner'=>json_decode($response)->owner
                ];
            }
        }
        return view('template.campaigns.list',compact('adAccounts'));
    }

    public function campaigns_api(){
        $user = Auth::user();
        $ad_account = \request()->get("ad_account");
        $start_date = \request()->get("start_date");
        $end_date = \request()->get("end_date");

        $access_token = $user->fb_access_token;
        $app_secret = env('FACEBOOK_CLIENT_SECRET');
        $app_id = env('FACEBOOK_CLIENT_ID');
        $api = Api::init($app_id, $app_secret, $access_token);
        $api->setLogger(new CurlLogger());
        $account = new AdAccount($ad_account);
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
            $campaign = new Campaign($single->id);
            $params = array(
                'time_range' => array(
                    'since' => (new DateTime($start_date))->format('Y-m-d'),
                    'until' => (new DateTime($end_date))->format('Y-m-d'),
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
                AdsInsightsFields::CPC,
                AdsInsightsFields::CPP,
                AdsInsightsFields::DDA_RESULTS,
                AdsInsightsFields::WISH_BID,
            );
            $insights = $campaign->getInsights($insight_fields, $params);

            $stats[] = [
                'responsive_id'=>'',
                'id'=>$insights[0]->campaign_id,
                'campaign_name'=>$insights[0]->campaign_name,
                'objective'=>$insights[0]->objective,
                'reach'=>$insights[0]->reach,
                'impressions'=>$insights[0]->impressions,
                'spend'=>$insights[0]->spend,
                'buying_type'=>$insights[0]->buying_type,
            ];
//        dd($single);
        }
        return response()->json(['data'=>$stats]);
//        if (\request()->ajax()) {
//            $adsApi = FacebookAds::init(env("FACEBOOK_ACCESS_TOKEN"));
//            $campaigns = $adsApi->campaigns()->all(
//                [
//                    "account_id",
//                    "adlabels",
//                    "bid_strategy",
//                    "boosted_object_id",
//                    "brand_lift_studies",
//                    "budget_rebalance_flag",
//                    "budget_remaining",
//                    "buying_type",
//                    "can_create_brand_lift_study",
//                    "can_use_spend_cap",
//                    "configured_status",
//                    "created_time",
//                    "daily_budget",
//                    "effective_status",
//                    "id", "6267784068614",
//                    "issues_info",
//                    "last_budget_toggling_time",
//                    "lifetime_budget",
//                    "name",
//                    "objective",
//                    "pacing_type",
//                    "promoted_object",
//                    "recommendations",
//                    "source_campaign",
//                    "source_campaign_id",
//                    "spend_cap",
//                    "start_time",
//                    "status",
//                    "stop_time",
//                    "topline_id",
//                    "updated_time",
//                    "adbatch",
//                    "execution_options",
//                    "iterative_split_test_configs",
//                    "upstream_events",
//                ]
//                , "act_348814429");
////            dd($campaigns);
//        $data = [];
//        foreach ($campaigns as $campaign){
//            $data[] = [
//                "responsive_id"=>'',
//                "account_id"=>$campaign->account_id,
//                "adlabels"=>$campaign->adlabels,
//                "bid_strategy"=>$campaign->bid_strategy,
//                "boosted_object_id"=>$campaign->boosted_object_id,
//                "brand_lift_studies"=>$campaign->brand_lift_studies,
//                "budget_rebalance_flag"=>$campaign->budget_rebalance_flag,
//                "budget_remaining"=>$campaign->budget_remaining ?? "N/A",
//                "buying_type"=>$campaign->buying_type,
//                "can_create_brand_lift_study"=>$campaign->can_create_brand_lift_study,
//                "can_use_spend_cap"=>$campaign->can_use_spend_cap,
//                "configured_status"=>$campaign->configured_status,
//                "created_time"=>date('d M Y H:i:s A',strtotime($campaign->created_time)),
//                "daily_budget"=>$campaign->daily_budget ?? "N/A",
//                "effective_status"=>$campaign->effective_status,
//                "id"=>$campaign->id,
//                "issues_info"=>$campaign->issues_info,
//                "last_budget_toggling_time"=>$campaign->last_budget_toggling_time,
//                "lifetime_budget"=>$campaign->lifetime_budget ?? "N/A",
//                "name"=>$campaign->name,
//                "objective"=>$campaign->objective,
//                "pacing_type"=>$campaign->pacing_type,
//                "promoted_object"=>$campaign->promoted_object,
//                "recommendations"=>$campaign->recommendations,
//                "source_campaign"=>$campaign->source_campaign,
//                "source_campaign_id"=>$campaign->source_campaign_id,
//                "spend_cap"=>$campaign->spend_cap,
//                "start_time"=>date('d M Y H:i:s A',strtotime($campaign->start_time)),
//                "status"=>$campaign->status,
//                "stop_time"=>$campaign->stop_time,
//                "topline_id"=>$campaign->topline_id,
//                "updated_time"=>$campaign->updated_time,
//                "adbatch"=>$campaign->adbatch,
//                "execution_options"=>$campaign->execution_options,
//                "iterative_split_test_configs"=>$campaign->iterative_split_test_configs,
//                "upstream_events"=>$campaign->upstream_events,
//            ];
//        }
//            return response()->json(['data'=>$data]);
//        }

//        return redirect(route('campaigns.list'));
    }

    public function access_token_expiration(){
        $user = Auth::user();
        $creation_date = $user->fb_access_token_created_at;
        $formated_date = new DateTime(date('Y/m/d',strtotime($creation_date)));
        $now = new DateTime(now()->format('Y/m/d'));
        if ($formated_date->diff($now)->days < 55){
            return 1;
        }
        return 0;
    }
}
