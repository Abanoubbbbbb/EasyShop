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
        Schema::table('order_items', function (Blueprint $table) {
            // 1. التأكد أن العمود غير موجود قبل إضافته
            if (!Schema::hasColumn('order_items', 'company_id')) {
                $table->unsignedBigInteger('company_id')->after('id')->nullable();

                // 2. إضافة الربط (Foreign Key) مع إعطاؤه اسم فريد لتجنب Error 121
                $table->foreign('company_id', 'fk_items_company_id_unique')
                    ->references('id')
                    ->on('companies')
                    ->onDelete('cascade');

                // 3. إضافة الأندكس للسرعة كما اتفقنا
                $table->index('company_id', 'idx_order_items_company');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // حذف الربط باستخدام الاسم الفريد اللي حددناه
            $table->dropForeign('fk_items_company_id_unique');
            $table->dropColumn('company_id');
        });
    }
};
