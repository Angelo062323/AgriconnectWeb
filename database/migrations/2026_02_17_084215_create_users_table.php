<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Role System
            $table->enum('role', ['farmer','lgu','da','admin'])->default('farmer');
            $table->enum('status', ['active','inactive'])->default('active');

            // Foreign References
            $table->foreignId('farmer_id')->nullable()->constrained('farmers')->nullOnDelete();
            $table->foreignId('lgu_id')->nullable()->constrained('lgus')->nullOnDelete();
            $table->foreignId('da_id')->nullable()->constrained('department_agriculture')->nullOnDelete();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
