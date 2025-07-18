<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('message');
            $table->unsignedBigInteger('added_id');
            $table->string('attachment', 255)->nullable();
            $table->boolean('notice_for_students')->default(false);
            $table->boolean('notice_for_teachers')->default(false);
            $table->boolean('notice_for_parents')->default(false);
            $table->boolean('notice_for_everyone')->default(false);
            $table->timestamps();

            // foreign key constraint
            $table->foreign('added_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
