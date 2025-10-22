<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelatedDocsToDocPendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doc_pending', function (Blueprint $table) {
            $table->text('related_docs')->nullable()->after('doc_preview_count');
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
            $table->dropColumn('related_docs');
        });
    }
}