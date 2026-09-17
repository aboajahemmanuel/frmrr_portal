<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutional_subscription_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_subscription_id');
            $table->unsignedBigInteger('owner_user_id');
            $table->string('email');
            $table->unsignedBigInteger('member_user_id')->nullable();
            $table->unsignedBigInteger('member_subscription_id')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutional_subscription_members');
    }
};
