<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            if (Schema::hasColumn('order_items', 'company_id')) {

                // حذف الـ foreign key لو موجود
                try {
                    $table->dropForeign(['company_id']);
                } catch (\Throwable $e) {
                }

                // حذف الـ index لو موجود
                try {
                    $table->dropIndex('idx_order_items_company');
                } catch (\Throwable $e) {
                }

                // حذف العمود
                $table->dropColumn('company_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }
};
