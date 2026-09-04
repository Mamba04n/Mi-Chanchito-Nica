<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // e.g. opening, in, out, adjustment_in, adjustment_out, transfer_in, transfer_out
            $table->string('type'); 
            
            $table->decimal('quantity', 15, 2);
            $table->decimal('previous_quantity', 15, 2);
            $table->decimal('new_quantity', 15, 2);
            
            $table->string('reference_type')->nullable(); // e.g., 'invoice', 'purchase_order', 'transfer'
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['company_id', 'product_id', 'occurred_at'], 'idx_kardex_company_product_date');
            $table->index(['warehouse_id', 'product_id']);
            $table->index(['company_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
