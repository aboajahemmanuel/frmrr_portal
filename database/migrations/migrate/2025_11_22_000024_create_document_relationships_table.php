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
        Schema::create('document_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_document_id')->constrained('regulations')->onDelete('cascade');
            $table->foreignId('related_document_id')->constrained('regulations')->onDelete('cascade');
            $table->string('relationship_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_relationships');
    }
};
