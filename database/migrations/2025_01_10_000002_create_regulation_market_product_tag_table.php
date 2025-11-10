<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulationMarketProductTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulation_market_product_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('regulation_id');
            $table->unsignedBigInteger('market_product_tag_id');
            $table->timestamps();

            $table->foreign('regulation_id')->references('id')->on('regulations')->onDelete('cascade');
            $table->foreign('market_product_tag_id')->references('id')->on('market_product_tags')->onDelete('cascade');
            
            // Prevent duplicate entries
            $table->unique(['regulation_id', 'market_product_tag_id'], 'regulation_tag_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regulation_market_product_tag');
    }
}
