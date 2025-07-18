<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_leave_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->integer('medical_leaves')->default(10);
            $table->integer('maternity_leaves')->default(10);
            $table->integer('casual_leaves')->default(10);
            $table->integer('sick_leaves')->default(10);
            $table->timestamps();

            $table->foreign('teacher_id')
                  ->references('id')->on('teachers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_leave_infos');
    }
};
