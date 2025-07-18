<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id()->comment('Unique subject ID');
            $table->string('name', 100)->comment('Subject name');
            $table->string('code', 20)->unique()->comment('Subject code like ENG101');
            $table->enum('type', ['Theory', 'Practical', 'Both'])->comment('Subject type');
            $table->integer('full_mark_theory')->nullable()->comment('Full mark for theory');
            $table->integer('full_mark_practical')->nullable()->comment('Full mark for practical');
            $table->integer('pass_mark_theory')->nullable()->comment('Pass mark for theory');
            $table->integer('pass_mark_practical')->nullable()->comment('Pass mark for practical');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
