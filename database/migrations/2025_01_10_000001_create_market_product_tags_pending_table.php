<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketProductTagsPendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_product_tags_pending', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('market_product_tag_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('inputer_id')->nullable();
            $table->unsignedBigInteger('authorizer_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=approved, 2=rejected');
            $table->string('action_type')->nullable()->comment('Insert, Edit, Delete');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('market_product_tag_id')->references('id')->on('market_product_tags')->onDelete('cascade');
            $table->foreign('inputer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('authorizer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_product_tags_pending');
    }
}
