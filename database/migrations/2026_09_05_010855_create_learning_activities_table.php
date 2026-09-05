<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('instructions');
            $table->integer('order');
            $table->boolean('required')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
