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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('total_sales', 10, 2)->nullable()->after('status');
            $table->decimal('commission_amount', 10, 2)->nullable()->after('total_sales');
            $table->decimal('net_payout', 10, 2)->nullable()->after('commission_amount');
            $table->enum('settlement_status', ['pending', 'completed'])->default('pending')->after('net_payout');
        });
    }
};
