<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('transfer_certificate')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('character_certificate')->nullable();
            $table->string('transcripts')->nullable();
            $table->enum('medical_condition', ['Good', 'Bad', 'Others'])->nullable();
            $table->string('allergies')->nullable();
            $table->string('medication')->nullable();
            $table->string('medical_document')->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                  ->references('id')->on('student_personal_info')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('student_documents');
    }
};
