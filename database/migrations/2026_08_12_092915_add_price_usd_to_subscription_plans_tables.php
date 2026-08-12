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
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->decimal('price_usd', 10, 2)->nullable()->after('price');
        });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->decimal('price_usd', 10, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('price_usd');
        });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->dropColumn('price_usd');
        });
    }
};
