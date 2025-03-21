<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('applicant_appointments', function (Blueprint $table) {
            $table->dropColumn('schedule_date'); // ✅ Drop the column
        });
    }

    public function down()
    {
        Schema::table('applicant_appointments', function (Blueprint $table) {
            $table->dateTime('schedule_date')->nullable(); // ✅ Restore if rolling back
        });
    }
};
