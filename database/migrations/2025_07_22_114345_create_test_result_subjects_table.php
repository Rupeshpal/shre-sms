<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('test_result_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('testResultId');
            $table->string('name')->nullable();
            $table->integer('fullMarks')->nullable();
            $table->integer('passMarks')->nullable();
            $table->integer('obtainedMarks')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();

            $table->foreign('testResultId')
                  ->references('id')
                  ->on('test_results')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('test_result_subjects');
    }
};
