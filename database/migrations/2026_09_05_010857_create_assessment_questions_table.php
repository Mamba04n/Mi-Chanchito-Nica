<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->string('question_type');
            $table->text('question');
            $table->text('explanation')->nullable();
            $table->integer('points')->default(10);
            $table->integer('order');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
