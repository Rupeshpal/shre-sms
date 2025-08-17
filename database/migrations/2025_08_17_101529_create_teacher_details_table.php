<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_documents', function (Blueprint $table) {
            $table->id();
            $table->string('joining_letter')->nullable();
            $table->string('experience_certificate')->nullable();
            $table->string('character_certificate')->nullable();
            $table->string('main_sheets')->nullable();
            $table->string('medical_condition_file')->nullable();
            $table->enum('medical_status', ['Good', 'Bad', 'Others']);
            $table->string('allergies')->nullable();
            $table->string('medication')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_documents');
    }
};
