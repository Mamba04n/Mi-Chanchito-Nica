<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_program_source', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_program_id')->constrained('learning_programs')->onDelete('cascade');
            $table->foreignId('educational_source_id')->constrained('educational_sources')->onDelete('cascade');
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
