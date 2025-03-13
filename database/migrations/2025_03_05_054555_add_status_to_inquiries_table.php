<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('status')->default('pending'); // pending, archived
        });
    }

    public function down() {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
