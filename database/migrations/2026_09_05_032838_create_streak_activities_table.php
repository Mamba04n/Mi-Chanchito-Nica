<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('streak_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('activity_date');
            $table->string('source_type');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unique(['user_id', 'activity_date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('streak_activities');
    }
};