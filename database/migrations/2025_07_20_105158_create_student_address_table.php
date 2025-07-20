<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('temp_street', 255)->nullable();
            $table->string('temp_city', 100)->nullable();
            $table->string('temp_state', 100)->nullable();
            $table->string('temp_country', 100)->nullable();
            $table->string('perm_street', 255)->nullable();
            $table->string('perm_city', 100)->nullable();
            $table->string('perm_state', 100)->nullable();
            $table->string('perm_country', 100)->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')
                ->on('student_personal_info')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_address');
    }
};
