<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RegulationApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulation_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id');
            $table->enum('status', ['approved', 'rejected']);
            $table->text('note')->nullable();
            $table->foreignId('authoriser_id');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
