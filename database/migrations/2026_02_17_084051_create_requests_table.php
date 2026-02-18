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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lgu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('da_id')->nullable()->constrained('department_agriculture')->nullOnDelete();

            $table->enum('request_type', [
                'plant_seeds',
                'fertilizer',
                'pesticides',
                'other',
            ]);
            $table->text('description');

            $table->enum('priority', ['low','medium','high'])->default('medium');

            $table->enum('status', [
                'pending',
                'reviewed_by_lgu',
                'approved_by_lgu',
                'forwarded_to_da',
                'approved_by_da',
                'rejected',
                'completed'
            ])->default('pending');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
