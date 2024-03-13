<?php

namespace App\Console\Commands;

use App\Http\Controllers\OrderController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Automattic\WooCommerce\Client;

class ImportOrdersWp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:orders_wp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Wordpress Orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::alert("===========================================================");
        Log::info("Importing Orders for WordPress Time: ".now()->format("d M Y: H:i:s"));
        $woocommerce = new Client(
            'https://lojacasa741.com.br',
            'ck_92b5b2159edfc840771565ea70dd2cb764746a4f',
            'cs_d8393862e8854946972c7436b2b1a5e9629b7126',
            [
                'version' => 'wc/v3',
            ]
        );
        $results = $woocommerce->get('orders',['context'=>'view','status'=>'any']);
        OrderController::fetch_wp_orders($results);
        return 0;
    }
}
