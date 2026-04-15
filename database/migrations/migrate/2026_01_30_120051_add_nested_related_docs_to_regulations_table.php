<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNestedRelatedDocsToRegulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regulations', function (Blueprint $table) {
            $table->text('nested_related_docs')->nullable()->after('related_docs');
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
            $table->dropColumn('nested_related_docs');
        });
    }
}
