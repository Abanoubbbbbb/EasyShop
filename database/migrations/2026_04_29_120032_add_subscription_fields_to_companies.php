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
        Schema::table('companies', function (Blueprint $table) {
            // نوع الاشتراك
            $table->enum('plan', ['free', 'monthly', 'yearly'])->default('free')->index();

            // السعر (اختياري للإدارة)
            $table->decimal('subscription_price', 10, 2)->default(0);

            // تاريخ انتهاء الاشتراك
            $table->date('subscription_ends_at')->nullable()->index();

            // هل الشركة شغالة ولا لا
            $table->boolean('is_active')->default(true)->index();

            // قبل كام يوم تبعت notification
            $table->unsignedInteger('notify_before_days')->default(3);

            // عشان نمنع تكرار الإيميل
            $table->boolean('notified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
};
