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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'discount_code_id')) {
                $table->unsignedBigInteger('discount_code_id')->nullable()->after('total');
            }
            // Xóa ràng buộc cũ nếu có
            $table->dropForeign(['discount_code_id']);
            // Thêm ràng buộc khóa ngoại
            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['discount_code_id']);
            $table->dropColumn('discount_code_id');
        });
    }
};
