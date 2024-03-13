<?php

namespace App\Console\Commands;

use App\Http\Controllers\OrderController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:orders {shop_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import All Shops Orders from Shopify';

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
        $shop_name =  $this->argument('shop_name');
        if (!empty($shop_name))
            Log::info("Importing Orders for Shop: ".$shop_name."   Time: ".now()->format("d M Y: H:i:s"));
        else
            Log::info("Importing Orders for All Shops  Time:".now()->format("d M Y: H:i:s"));

        OrderController::fetch_store_orders($shop_name ?? NULL);
        return 0;
    }
}
