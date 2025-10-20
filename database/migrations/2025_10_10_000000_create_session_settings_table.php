<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSessionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('timeout_minutes')->default(15); // Default to 15 minutes
            $table->timestamps();
        });
        
        // Insert default setting
        DB::table('session_settings')->insert([
            'timeout_minutes' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_settings');
    }
}