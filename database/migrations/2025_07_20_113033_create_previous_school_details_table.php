<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('previous_school_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); 
            $table->string('school_name', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('affiliation_board', 50)->nullable();
            $table->string('school_contact_number', 20)->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                  ->references('id')
                  ->on('student_personal_info')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('previous_school_details');
    }
};