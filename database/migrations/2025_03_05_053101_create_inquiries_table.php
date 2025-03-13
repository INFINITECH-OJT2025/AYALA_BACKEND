<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('inquiry_type'); // Sales, Leasing, Customer Case, etc.
            $table->string('last_name');
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('inquiries');
    }
};
