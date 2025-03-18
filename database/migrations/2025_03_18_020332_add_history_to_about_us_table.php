<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->json('history')->nullable()->after('vision_description'); // ✅ Add history column
        });
    }

    public function down()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn('history'); // ✅ Drop column if rolled back
        });
    }
};

