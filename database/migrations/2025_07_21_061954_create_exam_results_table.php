<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('admissionNo');
            $table->string('name')->nullable();
            $table->string('rollNo')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->integer('science')->nullable();
            $table->integer('chemistry')->nullable();
            $table->integer('math')->nullable();
            $table->integer('social')->nullable();
            $table->integer('obtainedMarks')->nullable();
            $table->integer('total')->nullable();
            $table->string('percentage')->nullable();
            $table->string('grade')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
