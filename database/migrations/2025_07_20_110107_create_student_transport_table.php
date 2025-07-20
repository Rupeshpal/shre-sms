<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_transport', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('route', 100)->nullable();
            $table->string('vehicle_number', 50)->nullable();
            $table->decimal('monthly_fare', 10, 2)->nullable();
            $table->string('pickup_point', 100)->nullable();
            $table->timestamps();
            $table->foreign('student_id')
                ->references('id')
                ->on('student_personal_info')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transport');
    }
};
