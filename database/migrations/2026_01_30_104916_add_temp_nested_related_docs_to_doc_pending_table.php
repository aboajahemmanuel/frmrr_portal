<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempNestedRelatedDocsToDocPendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doc_pending', function (Blueprint $table) {
            $table->text('temp_nested_related_docs')->nullable();
            $table->text('temp_relationship_types')->nullable();
            $table->text('temp_relationship_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doc_pending', function (Blueprint $table) {
            $table->dropColumn(['temp_nested_related_docs', 'temp_relationship_types', 'temp_relationship_notes']);
        });
    }
}
