<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coins extends Model
{
    use HasFactory;
    protected $fillable=['name','price','circulating_supply','updated_at','total_supply','fully_diluted_market_cap','market_cap','num_market_pairs','old_market_cap','total_market_cap'];

    protected $primaryKey='IDs';

}
