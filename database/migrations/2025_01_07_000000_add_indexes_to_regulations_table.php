<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToRegulationsTable extends Migration
{
    /**
     * Run the migrations.
     * Add indexes to optimize query performance and prevent timeouts
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regulations', function (Blueprint $table) {
            // Add composite index for frequently queried columns
            $table->index(['status', 'category_id', 'ceased'], 'idx_status_category_ceased');
            
            // Add index for year and entity lookups
            $table->index('year_id', 'idx_year_id');
            $table->index('entity_id', 'idx_entity_id');
            
            // Add index for subcategory filtering
            $table->index(['status', 'subcategory_id', 'ceased'], 'idx_status_subcategory_ceased');
            
            // Add index for ordering
            $table->index('created_at', 'idx_created_at');
            
            // Add index for related documents lookup (if searching by related_docs)
            $table->index('related_docs', 'idx_related_docs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regulations', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropIndex('idx_related_docs');
            $table->dropIndex('idx_created_at');
            $table->dropIndex('idx_status_subcategory_ceased');
            $table->dropIndex('idx_entity_id');
            $table->dropIndex('idx_year_id');
            $table->dropIndex('idx_status_category_ceased');
        });
    }
}
