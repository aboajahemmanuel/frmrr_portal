<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarketProductTagToRegulationsAndDocumentApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regulations', function (Blueprint $table) {
            $table->text('market_product_tag')->nullable()->after('related_docs');
        });

        Schema::table('doc_pending', function (Blueprint $table) {
            $table->text('market_product_tag')->nullable()->after('related_docs');
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
            $table->dropColumn('market_product_tag');
        });

        Schema::table('document_approvals', function (Blueprint $table) {
            $table->dropColumn('market_product_tag');
        });
    }
}
