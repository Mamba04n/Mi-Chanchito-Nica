<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_program_id')->constrained('learning_programs')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('order');
            $table->integer('estimated_duration_minutes')->nullable();
            $table->string('status');
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
