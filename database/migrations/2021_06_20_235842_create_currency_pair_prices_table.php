<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyPairPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_pair_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('currency_pair_id');
            $table->double('last');
            $table->double('high');
            $table->double('low');
            $table->double('volume');
            $table->double('bid');
            $table->double('ask');
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
        Schema::dropIfExists('currency_pair_prices');
    }
}
