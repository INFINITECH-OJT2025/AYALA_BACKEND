<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('applicant_reschedules', function (Blueprint $table) {
            $table->dropColumn('new_time');
        });
    }

    public function down() {
        Schema::table('applicant_reschedules', function (Blueprint $table) {
            $table->dateTime('new_time')->nullable();
        });
    }
};
