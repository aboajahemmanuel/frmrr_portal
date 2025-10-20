<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_relationships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_document_id'); // The document that has the relationship
            $table->unsignedBigInteger('related_document_id'); // The document being related to
            $table->string('relationship_type'); // Supersedes, Amended, Ceased, Repealed, Active Amendment, Reference, etc.
            $table->string('product_type')->nullable(); // Product type (bonds, T. bills, etc.)
            $table->string('status')->nullable(); // Relationship status
            $table->text('notes')->nullable(); // Additional notes about the relationship
            $table->unsignedBigInteger('created_by'); // User who created the relationship
            $table->unsignedBigInteger('group_id'); // Group ID for multi-tenancy
            $table->boolean('is_active')->default(true); // Whether the relationship is active
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('source_document_id')->references('id')->on('regulations')->onDelete('cascade');
            $table->foreign('related_document_id')->references('id')->on('regulations')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['source_document_id', 'relationship_type']);
            $table->index(['related_document_id', 'relationship_type']);
            $table->index('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_relationships');
    }
}
