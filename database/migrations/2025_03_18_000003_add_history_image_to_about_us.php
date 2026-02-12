<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('about_us', 'history')) {
            Schema::table('about_us', function (Blueprint $table) {
                $table->json('history')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('about_us', 'history')) {
            Schema::table('about_us', function (Blueprint $table) {
                $table->dropColumn('history');
            });
        }
    }
};
