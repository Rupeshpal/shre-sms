<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_siblings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('name', 100)->nullable();
            $table->string('admission_no', 50)->nullable();
            $table->string('section', 50)->nullable();
            $table->string('roll_no', 20)->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')
                ->on('student_personal_info')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_siblings');
    }
};
