<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_verification_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('institution_name');
            $table->string('student_id_number')->nullable();
            $table->string('proof_path');
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_verification_requests');
    }
};
