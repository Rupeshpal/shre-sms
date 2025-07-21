<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2025_07_21_000004_create_test_result_subjects_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('test_result_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testResultId')->constrained('test_results')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('fullMarks')->nullable();
            $table->integer('passMarks')->nullable();
            $table->integer('obtainedMarks')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('test_result_subjects');
    }
};
