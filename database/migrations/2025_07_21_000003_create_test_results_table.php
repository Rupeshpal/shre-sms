<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2025_07_21_000003_create_test_results_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->string('testName')->nullable();
            $table->string('status')->nullable();
            $table->integer('rank')->nullable();
            $table->integer('totalMarks')->nullable();
            $table->integer('passMarks')->nullable();
            $table->integer('obtainedMarks')->nullable();
            $table->integer('passPercentage')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('test_results');
    }
};
