<?php

namespace App\Http\Resources;

use App\Models\Coins;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class                                                                                                             MarketCapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): Array
    {
        $coin= Coins::find($this->id);
        return [


            'name' => $this->name,
            'symbol' => $this->symbol,
            'old_market_cap' =>  new MarketCapDBResource($coin),
            'num_market_pairs' => $this->num_market_pairs,
            'market_cap' => $this->quote->USD->market_cap,
            'fully_diluted_market_cap' => $this->quote->USD->fully_diluted_market_cap,
            'total_supply' => $this->total_supply,
            'circulating_supply' => $this->circulating_supply,
            'updated_at' => Carbon::parse($this->quote->USD->last_updated),


        ];

    }


    public function with($request)
    {
        return [
            'meta' => [
                'key' => 'value',
            ],
        ];
    }
}
