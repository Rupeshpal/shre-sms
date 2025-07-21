<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
         $table->string('id', 20)->primary();
         $table->string('class')->nullable();
         $table->string('section')->nullable();
         $table->string('subject')->nullable();
         $table->date('date')->nullable();
         $table->integer('passMark')->nullable();
         $table->string('startTime')->nullable();
         $table->string('duration')->nullable();
         $table->string('roomNo')->nullable();
         $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
