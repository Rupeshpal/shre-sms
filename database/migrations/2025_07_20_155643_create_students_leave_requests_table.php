<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // student user id
            $table->enum('leave_type', ['sick', 'casual', 'earned', 'maternity', 'other']);
            $table->date('leave_date');
            $table->date('end_date')->nullable();
            $table->timestamp('applied_on')->useCurrent();
            $table->integer('no_of_days')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->timestamp('decision_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students_leave_requests');
    }
};
