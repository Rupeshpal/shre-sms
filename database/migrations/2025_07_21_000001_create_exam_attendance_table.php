<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exam_attendance', function (Blueprint $table) {
            $table->string('id',50)->primary();
            $table->string('admissionNo');
            $table->string('rollNo')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->string('student')->nullable();
            $table->string('science')->nullable();
            $table->string('chemistry')->nullable();
            $table->string('math')->nullable();
            $table->string('social')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('exam_attendance');
    }
};
