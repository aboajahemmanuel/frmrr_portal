<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add summary to main subcategories table
        if (Schema::hasTable('subcategories') && !Schema::hasColumn('subcategories', 'summary')) {
            Schema::table('subcategories', function (Blueprint $table) {
                $table->text('summary')->nullable()->after('slug');
            });
        }

        // Add summary to pending table used by approval workflow
        if (Schema::hasTable('subcategories_pendings') && !Schema::hasColumn('subcategories_pendings', 'summary')) {
            Schema::table('subcategories_pendings', function (Blueprint $table) {
                $table->text('summary')->nullable()->after('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'summary')) {
            Schema::table('subcategories', function (Blueprint $table) {
                $table->dropColumn('summary');
            });
        }
        if (Schema::hasTable('subcategories_pendings') && Schema::hasColumn('subcategories_pendings', 'summary')) {
            Schema::table('subcategories_pendings', function (Blueprint $table) {
                $table->dropColumn('summary');
            });
        }
    }
};
