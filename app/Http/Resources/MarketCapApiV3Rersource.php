<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarketCapApiV3Rersource extends JsonResource
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

            'id'=>$this->IDs,
            'previous_market_cap' => $this->market_cap,
            'previous_dominance' => (($this->market_cap / databaseGlobalMetrics()->total_market_cap)*100),
            'previous_price' => $this->price,


        ];
    }
}
