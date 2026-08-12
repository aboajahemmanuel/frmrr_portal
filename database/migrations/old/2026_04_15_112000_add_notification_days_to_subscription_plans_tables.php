<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationDaysToSubscriptionPlansTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('subscription_plans', function (Blueprint $table) {
        //     $table->integer('notification_days')->default(0)->after('description');
        // });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->integer('notification_days')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('notification_days');
        });

        Schema::table('subscription_plans_pendings', function (Blueprint $table) {
            $table->dropColumn('notification_days');
        });
    }
}
