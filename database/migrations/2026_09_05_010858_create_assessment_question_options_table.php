<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_question_id')->constrained('assessment_questions')->onDelete('cascade');
            $table->text('text');
            $table->boolean('is_correct');
            $table->integer('order');
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
