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
        Schema::create('doc_pending', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')->nullable()->constrained('regulations')->onDelete('set null');
            $table->foreignId('inputter_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('authoriser_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->nullable();
            $table->string('action_type')->nullable();
            $table->string('title');
            $table->date('effective_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('document_version')->nullable();
            $table->foreignId('year_id')->nullable()->constrained('years')->onDelete('set null');
            $table->foreignId('month_id')->nullable()->constrained('months')->onDelete('set null');
            $table->foreignId('entity_id')->nullable()->constrained('entities')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories')->onDelete('set null');
            $table->string('alpha_id')->nullable();
            $table->string('document_tag')->nullable();
            $table->date('ceased_date')->nullable();
            $table->boolean('ceased')->default(false);
            $table->text('doc_preview')->nullable();
            $table->integer('doc_preview_count')->default(0);
            $table->text('related_docs')->nullable();
            $table->text('market_product_tag')->nullable();
            $table->string('regulation_doc')->nullable();
            $table->string('slug')->nullable();
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->timestamp('authoriser_time')->nullable();
            $table->timestamp('inputter_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_pending');
    }
};
