<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_attempt_id')->constrained('assessment_attempts')->onDelete('cascade');
            $table->foreignId('assessment_question_id')->constrained('assessment_questions')->onDelete('cascade');
            $table->foreignId('assessment_question_option_id')->nullable()->constrained('assessment_question_options')->onDelete('cascade');
            $table->text('answer_data')->nullable();
            $table->decimal('points_awarded', 5, 2)->default(0);
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
