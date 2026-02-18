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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lgu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('da_id')->nullable()->constrained('department_agriculture')->nullOnDelete();

            $table->string('report_type');
            $table->enum('severity', ['minor','moderate','severe'])->default('minor');
            $table->text('description');

            $table->enum('status', [
                'pending',
                'validated_by_lgu',
                'forwarded_to_da',
                'resolved'
            ])->default('pending');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
