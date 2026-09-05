<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->onDelete('cascade');
            $table->foreignId('learning_unit_id')->nullable()->constrained('learning_units')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('assessment_type');
            $table->decimal('passing_score', 5, 2)->default(70.0);
            $table->integer('max_attempts')->nullable();
            $table->integer('time_limit_minutes')->nullable();
            $table->string('status');
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
