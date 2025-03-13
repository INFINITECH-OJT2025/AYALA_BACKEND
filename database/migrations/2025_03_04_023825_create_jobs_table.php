<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('type')->default('Full-time');
            $table->string('category')->nullable();
            $table->string('salary')->nullable();
            $table->date('deadline')->nullable();
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('jobs');
    }
};

