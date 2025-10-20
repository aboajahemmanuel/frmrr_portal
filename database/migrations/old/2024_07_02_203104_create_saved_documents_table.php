<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('saved_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('document_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saved_documents');
    }
}
