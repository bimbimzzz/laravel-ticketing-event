<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old enum column (SQLite doesn't support ALTER enum)
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_payment');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_payment')->default('pending')->after('total_price');
            $table->text('cancel_reason')->nullable()->after('payment_url');
            $table->timestamp('cancelled_at')->nullable()->after('cancel_reason');
            $table->text('refund_note')->nullable()->after('cancelled_at');
            $table->timestamp('refunded_at')->nullable()->after('refund_note');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'cancelled_at', 'refund_note', 'refunded_at']);
            $table->dropColumn('status_payment');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status_payment', ['pending', 'success', 'cancel'])->default('pending')->after('total_price');
        });
    }
};
