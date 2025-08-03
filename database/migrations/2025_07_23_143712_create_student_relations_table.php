<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_personal_info')->onDelete('cascade');
            $table->enum('relation', ['Father', 'Mother', 'Guardian']);
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('temporary_address', 255)->nullable();
            $table->string('permanent_address', 255)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('document', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_relations');
    }
};
