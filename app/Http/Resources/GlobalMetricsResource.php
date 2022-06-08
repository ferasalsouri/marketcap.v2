<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GlobalMetricsResource extends JsonResource
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
            'total_market_cap'=>$this->quote->USD->total_market_cap,
            'total_market_cap_yesterday'=>$this->quote->USD->total_market_cap_yesterday,
        ];
    }
}
