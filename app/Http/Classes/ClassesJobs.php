<?php namespace App\Http\Classes;


use App\Models\Coins;
use Carbon\Carbon;

class ClassesJobs
{

    protected $Api = '97244566-eba0-43a7-a5ff-055add7ae8d5';

    public function ConnectionCoinMarketCap()
    {
        return new \CoinMarketCap\Api($this->Api);

    }


    public function periodStoreTime()
    {
        $response = $this->ConnectionCoinMarketCap()->cryptocurrency()->listingsLatest(['cryptocurrency_type' => 'all', 'convert' => 'USD', 'limit' => 5000]);

        for ($i = 0; $i <= 4999; $i++) {
            $data[] = [
                'IDs' => $response->data[$i]->id,
                'name' => $response->data[$i]->name,
                'symbol' => $response->data[$i]->symbol,
                'price' => $response->data[$i]->quote->USD->price,
                'market_cap' => $response->data[$i]->quote->USD->market_cap,
                'fully_diluted_market_cap' => $response->data[$i]->quote->USD->fully_diluted_market_cap,
                'total_supply' => $response->data[$i]->total_supply,
                'circulating_supply' => $response->data[$i]->circulating_supply,
                'num_market_pairs' => $response->data[$i]->num_market_pairs,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::parse($response->data[$i]->quote->USD->last_updated),
            ];


        }
        if (Coins::count() > 0)
            Coins::query()->truncate();
        $chunks = array_chunk($data, 5000);

        foreach ($chunks as $chunk) {

            Coins::insert($chunk);

        }
        return true;
    }

}
