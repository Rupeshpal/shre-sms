<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('sections', function (Blueprint $table) {
    $table->string('id', 10)->primary();
    $table->string('section_name', 255);
    $table->tinyInteger('status')->default(1);
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
