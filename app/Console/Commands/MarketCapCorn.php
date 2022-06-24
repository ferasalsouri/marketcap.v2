<?php

namespace App\Console\Commands;

use App\Http\Classes\ClassesJobs;
use Illuminate\Console\Command;

class MarketCapCorn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:coins-marketcap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to insert new coins from market cap api service';

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
        $storeCoins= new ClassesJobs();

        $storeCoins->periodStoreTime();
        return 0;
    }



}
