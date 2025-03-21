<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('job_title')->after('id'); // ✅ Adds job title column
        });
    }

    public function down() {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('job_title'); // ✅ Rolls back if needed
        });
    }
};
