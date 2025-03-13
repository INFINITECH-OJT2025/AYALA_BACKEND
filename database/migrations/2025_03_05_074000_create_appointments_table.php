<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade'); // ✅ Foreign key
            $table->string('last_name');
            $table->string('first_name');
            $table->string('email');
            $table->string('phone');
            $table->date('date');
            $table->time('time');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('appointments');
    }
};
