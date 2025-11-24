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
        Schema::create('regulations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->foreignId('month_id')->nullable()->constrained('months')->onDelete('set null');
            $table->foreignId('year_id')->nullable()->constrained('years')->onDelete('set null');
            $table->string('document_tag')->nullable();
            $table->string('alpha_id')->nullable();
            $table->foreignId('entity_id')->nullable()->constrained('entities')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories')->onDelete('set null');
            $table->string('regulation_doc')->nullable();
            $table->string('regulation_doc2')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('status')->default('active');
            $table->text('note')->nullable();
            $table->boolean('ceased')->default(false);
            $table->date('effective_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('document_version')->nullable();
            $table->date('ceased_date')->nullable();
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->text('doc_preview')->nullable();
            $table->integer('doc_preview_count')->default(0);
            $table->string('admin_status')->nullable();
            $table->text('related_docs')->nullable();
            $table->text('market_product_tag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulations');
    }
};
