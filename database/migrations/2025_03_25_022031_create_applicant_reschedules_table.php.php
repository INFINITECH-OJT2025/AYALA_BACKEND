<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('applicant_reschedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('job_applications')->onDelete('cascade');
            $table->string('email'); // ✅ Store email for verification
            $table->dateTime('requested_schedule');
            $table->text('admin_message')->nullable();
            $table->dateTime('new_schedule')->nullable();
            $table->text('applicant_message')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('applicant_reschedules');
    }
};
