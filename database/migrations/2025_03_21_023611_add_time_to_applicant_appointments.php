<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('applicant_appointments', function (Blueprint $table) {
            $table->dateTime('schedule_datetime')->after('applicant_id')->nullable(); // ✅ Store both date & time
        });
    }

    public function down()
    {
        Schema::table('applicant_appointments', function (Blueprint $table) {
            $table->dropColumn('schedule_datetime');
        });
    }
};

