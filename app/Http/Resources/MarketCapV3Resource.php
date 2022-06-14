<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketCapV3Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'num_market_pairs' => $this->num_market_pairs,
            'market_cap' => $this->quote->USD->market_cap,
            'price' => $this->quote->USD->price,
//            'price' =>  number_format($this->quote->USD->price, 8, ',', '.'),// number_format($this->quote->USD->price, 2),
            'fully_diluted_market_cap' => $this->quote->USD->fully_diluted_market_cap,
            'total_supply' => $this->total_supply,
            'circulating_supply' => $this->circulating_supply,
            'updated_at' => Carbon::parse($this->quote->USD->last_updated),

        ];
    }
}
