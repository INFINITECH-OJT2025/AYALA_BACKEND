<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('applicant_reschedules', function (Blueprint $table) {
            $table->time('new_time')->nullable()->after('new_schedule'); // ✅ Add new_time column
            $table->string('file_path', 255)->change(); // ✅ Ensure longer file paths
            $table->index('email'); // ✅ Improve query performance for email lookups
        });
    }

    public function down() {
        Schema::table('applicant_reschedules', function (Blueprint $table) {
            $table->dropColumn('new_time'); // ✅ Remove time if rollback
            $table->dropIndex(['email']); // ✅ Remove index if rollback
            $table->string('file_path')->change(); // ✅ Revert file_path changes
        });
    }
};
