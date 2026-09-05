<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('financial_account_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('NIO');
            $table->nullableMorphs('reference');
            $table->string('description');
            $table->text('notes')->nullable();
            $table->dateTime('occurred_at');
            $table->foreignId('created_by')->constrained('users');
            $table->decimal('previous_balance', 15, 2);
            $table->decimal('new_balance', 15, 2);
            $table->timestamps();

            $table->index('company_id');
            $table->index('financial_account_id');
            $table->index('type');
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_movements');
    }
};
