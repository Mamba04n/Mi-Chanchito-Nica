<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_unit_id')->constrained('learning_units')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('content_type')->default('markdown');
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
