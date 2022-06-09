<?php namespace App\Http\Classes;


use App\Http\Resources\GlobalMetricsResource;
use App\Models\Coins;
use App\Models\MarketCapInfo;
use Carbon\Carbon;

class ClassesJobs
{

    //'67d0f753-9502-46d6-8aa6-40eaedf9744d';
    //// '97244566-eba0-43a7-a5ff-055add7ae8d5';
    private $Api = '41920b5b-5276-438d-b187-55f443748d7a';



    public function ConnectionCoinMarketCap()
    {

        return new \CoinMarketCap\Api($this->Api);

    }


    public function periodStoreTime()
    {

        $response = $this->ConnectionCoinMarketCap()->cryptocurrency()->listingsLatest(['limit' => 5000, 'convert' => 'USD']);

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

    public function marketCapInfo()
    {

        $response = $this-> globalMetric();


        if (MarketCapInfo::count() > 0)
            MarketCapInfo::query()->truncate();

        MarketCapInfo::create([
            'total_market_cap' => $response->quote->USD->total_market_cap,
            'total_market_cap_yesterday' => $response->quote->USD->total_market_cap_yesterday,
            'last_update' => Carbon::parse($response->quote->USD->last_updated),
        ]);


        return true;
    }

    public function globalMetric()
    {


        $response = $this->ConnectionCoinMarketCap()->globalMetrics()->quotesLatest(['convert' => 'USD']);

        return $response->data;


    }

}
