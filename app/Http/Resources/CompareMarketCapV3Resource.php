<?php

namespace App\Http\Resources;

use App\Http\Classes\ClassesJobs;
use App\Models\Coins;
use App\Models\MarketCapInfo;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CompareMarketCapV3Resource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // from db
        $past_total_market_cap = databaseGlobalMetrics()->total_market_cap;
        $marketCapCoin = Coins::find($this->id);


        return [

            'id' => $this->id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'dominance' => (float) number_format( $this->quote->USD->market_cap_dominance, 3, '.', ''),
            'db_dominance' => isset($marketCapCoin['market_cap'])? (float) number_format((float) (($marketCapCoin['market_cap'] / $past_total_market_cap) * 100), 3, '.', ''): null,

            'updated_at' => Carbon::parse($this->quote->USD->last_updated),


        ];
    }
}
