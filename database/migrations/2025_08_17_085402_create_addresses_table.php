<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_addresses', function (Blueprint $table) {
            $table->id();

            // Permanent Address
            $table->string('permanent_street')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_state')->nullable();
            $table->string('permanent_country')->nullable();

            // Temporary Address
            $table->boolean('is_temp_same_as_perm')->default(false);
            $table->string('temporary_street')->nullable();
            $table->string('temporary_city')->nullable();
            $table->string('temporary_state')->nullable();
            $table->string('temporary_country')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_addresses');
    }
};
