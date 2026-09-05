<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('educational_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educational_source_id')->constrained('educational_sources')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('original_url');
            $table->string('document_type');
            $table->string('language');
            $table->date('published_at')->nullable();
            $table->string('processing_status');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
