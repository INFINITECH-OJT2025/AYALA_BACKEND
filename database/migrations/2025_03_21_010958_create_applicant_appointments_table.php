<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('applicant_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('job_applications')->onDelete('cascade');
            $table->dateTime('schedule_date');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('applicant_appointments');
    }
};
