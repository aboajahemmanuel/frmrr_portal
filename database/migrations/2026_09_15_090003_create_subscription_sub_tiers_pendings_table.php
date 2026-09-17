<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_sub_tiers_pendings', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_tier_id')->nullable();
            $table->string('subscription_sub_tier_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('action_type')->nullable();
            $table->unsignedBigInteger('inputer_id')->nullable();
            $table->unsignedBigInteger('authorizer_id')->nullable();
            $table->string('status')->default(0);
            $table->text('note')->nullable();
            $table->string('group_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_sub_tiers_pendings');
    }
};
