<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('regulation_market_product_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')->constrained('regulations')->onDelete('cascade');
            $table->foreignId('market_product_tag_id')->constrained('market_product_tags')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['regulation_id', 'market_product_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulation_market_product_tag');
    }
};
