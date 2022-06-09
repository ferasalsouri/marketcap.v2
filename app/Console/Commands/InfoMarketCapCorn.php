<?php

namespace App\Console\Commands;

use App\Http\Classes\ClassesJobs;
use Illuminate\Console\Command;

class InfoMarketCapCorn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:marketcap-Info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $storeCoins->marketCapInfo();
        return 0;
    }
}
