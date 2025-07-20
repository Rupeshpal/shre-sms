<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 20)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('semester_count');
            $table->integer('exam_count');
            $table->boolean('status')->default(1); 
            $table->boolean('current_academic_year')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};