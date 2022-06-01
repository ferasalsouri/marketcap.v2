<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->integer('IDs');
            $table->string('name');
            $table->string('symbol');
            $table->double('market_cap');
            $table->double('price');
            $table->double('fully_diluted_market_cap');
            $table->double('total_supply');
            $table->double('circulating_supply');
            $table->double('num_market_pairs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}
