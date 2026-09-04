<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // owner, admin, manager, operator, viewer
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. inventory.view
            $table->string('name');
            $table->string('module_key')->nullable(); // to group by module
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->unique(['role_id', 'permission_id']);
        });

        // Update company_user to use foreign key instead of string for role
        Schema::table('company_user', function (Blueprint $table) {
            // First drop the old string column
            $table->dropColumn('role_id');
        });

        Schema::table('company_user', function (Blueprint $table) {
            // Then add the new foreign key column (nullable initially or default to viewer, but we'll re-seed anyway)
            $table->foreignId('role_id')->nullable()->constrained('roles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('company_user', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::table('company_user', function (Blueprint $table) {
            $table->string('role_id')->default('member');
        });

        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
