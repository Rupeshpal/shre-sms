<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_personal_info', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 20)->nullable();
            $table->string('admission_number', 50)->nullable();
            $table->date('admission_date')->nullable();
            $table->string('roll_no', 20)->nullable();
            $table->boolean('status')->default(1);
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('class', 50)->nullable();
            $table->string('section', 50)->nullable();
            $table->string('gender', 40)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('house', 50)->nullable();
            $table->string('mother_tongue', 50)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_personal_info');
    }
};
