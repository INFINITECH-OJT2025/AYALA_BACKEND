<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->string('history_image')->nullable()->after('core_values'); // ✅ Add history_image column
        });
    }

    public function down()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn('history_image'); // ✅ Remove history_image on rollback
        });
    }
};
