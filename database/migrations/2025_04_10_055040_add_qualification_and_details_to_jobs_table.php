<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('qualification')->nullable()->after('slots'); // ✅ New field
            $table->string('seniority_level')->nullable()->after('qualification'); // ✅ New field
            $table->string('job_function')->nullable()->after('seniority_level'); // ✅ New field
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('qualification');
            $table->dropColumn('seniority_level');
            $table->dropColumn('job_function');
        });
    }
};
