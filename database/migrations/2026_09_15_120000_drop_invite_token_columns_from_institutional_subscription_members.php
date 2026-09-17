<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutional_subscription_members', function (Blueprint $table) {
            $table->dropColumn(['token', 'token_expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('institutional_subscription_members', function (Blueprint $table) {
            $table->string('token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
        });
    }
};
