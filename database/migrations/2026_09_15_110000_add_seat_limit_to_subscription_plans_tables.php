<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->unsignedTinyInteger('seat_limit')->nullable()->after('download_limit');
        });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->unsignedTinyInteger('seat_limit')->nullable()->after('download_limit');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('seat_limit');
        });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->dropColumn('seat_limit');
        });
    }
};
