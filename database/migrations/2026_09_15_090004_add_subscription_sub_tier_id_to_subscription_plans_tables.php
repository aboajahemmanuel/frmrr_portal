<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->string('subscription_sub_tier_id')->nullable()->after('group_id');
        });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->string('subscription_sub_tier_id')->nullable()->after('subscription_plans_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('subscription_sub_tier_id');
        });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->dropColumn('subscription_sub_tier_id');
        });
    }
};
