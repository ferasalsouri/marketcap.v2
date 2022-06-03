<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DataMarketCapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'symbol' => $this->symbol,
            'num_market_pairs' => $this->num_market_pairs,
            'old_market_cap' => $this->market_cap,
            'price' => $this->price,
            'fully_diluted_market_cap' => $this->fully_diluted_market_cap,
            'total_supply' => $this->total_supply,
            'circulating_supply' => $this->circulating_supply,
            'updated_at' => Carbon::parse($this->updated_at),
        ];
    }
}
