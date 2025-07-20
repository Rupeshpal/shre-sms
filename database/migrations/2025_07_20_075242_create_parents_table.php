<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
    $table->string('id', 20)->primary();
    $table->string('parent_name');
    $table->enum('gender', ['Male', 'Female', 'Other']);
    $table->string('nationality')->nullable();
    $table->string('occupation')->nullable();
    $table->string('primary_mobile_number');
    $table->string('alternate_contact_number')->nullable();
    $table->string('email_address')->nullable();
    $table->string('temporary_address')->nullable();
    $table->string('permanent_address')->nullable();
    $table->date('added_date')->nullable();
    $table->string('image')->nullable();
    $table->timestamps();
});

}

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
