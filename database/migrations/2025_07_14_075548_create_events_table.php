<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // id int [pk, increment]
            $table->string('title', 255);
            $table->string('category', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('message');
            $table->string('location', 100);
            $table->boolean('event_for_students')->default(false);
            $table->boolean('event_for_teachers')->default(false);
            $table->boolean('event_for_parents')->default(false);
            $table->boolean('event_for_everyone')->default(false);
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
