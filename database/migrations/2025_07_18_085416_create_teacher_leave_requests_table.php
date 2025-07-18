<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->enum('leave_type', ['Medical','Maternity','Casual','Sick','Other']);
            $table->date('leave_date');
            $table->date('end_date')->nullable();
            $table->integer('no_of_days')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->timestamp('applied_on')->useCurrent();
            $table->enum('status', ['Pending','Approved','Rejected'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')
                ->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('approver_id')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_leave_requests');
    }
};
