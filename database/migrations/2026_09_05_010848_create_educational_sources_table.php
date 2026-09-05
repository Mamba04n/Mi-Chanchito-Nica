<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('educational_sources', function (Blueprint $table) {
            $table->id();
            $table->string('institution');
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('source_type');
            $table->string('url');
            $table->string('language');
            $table->date('published_at')->nullable();
            $table->string('license')->nullable();
            $table->string('license_url')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('status');
            $table->timestamps();
        });
        }

    public function down(): void
    {
    }
};
