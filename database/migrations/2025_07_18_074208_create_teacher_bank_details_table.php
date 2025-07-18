<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->string('account_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('branch_name', 100)->nullable();
            $table->string('pan_number', 50)->nullable();
            $table->string('basic_salary', 50)->nullable();
            $table->string('contract_type', 50)->nullable();
            $table->string('work_location', 100)->nullable();
            $table->string('work_shift', 100)->nullable();
            $table->date('date_of_leaving')->nullable();
            $table->string('qualification', 150)->nullable();
            $table->string('work_experience', 50)->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('teacher_id')
                  ->references('id')->on('teachers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_bank_details');
    }
};
