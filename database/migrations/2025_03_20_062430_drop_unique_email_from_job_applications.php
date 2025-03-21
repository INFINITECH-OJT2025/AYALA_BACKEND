<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropUnique(['email']); // ✅ Drops unique constraint from email
        });
    }

    public function down() {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->unique('email'); // ✅ Re-adds unique constraint if rolled back
        });
    }
};
