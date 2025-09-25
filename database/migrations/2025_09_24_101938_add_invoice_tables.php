<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->string('filepath')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('invoice_metadata', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->onDelete('cascade');
            $table->string('invoice_number', 100);
            $table->date('issue_date');
            $table->string('supplier_id', 100);
            $table->string('customer_id', 100);
            $table->decimal('payable_amount', 15, 2);

            $table->index('invoice_id', 'fk_invoice_metadata_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_metadata');
        Schema::dropIfExists('invoices');
    }
};
