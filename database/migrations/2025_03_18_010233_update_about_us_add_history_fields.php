<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('about_us', function (Blueprint $table) {
            if (!Schema::hasColumn('about_us', 'history_title')) {
                $table->string('history_title')->nullable()->after('vision_description'); // ✅ Add history_title
            }

            if (!Schema::hasColumn('about_us', 'history_description')) {
                $table->text('history_description')->nullable()->after('history_title'); // ✅ Add history_description
            }

            if (!Schema::hasColumn('about_us', 'history_image')) {
                $table->string('history_image')->nullable()->after('history_description'); // ✅ Ensure history_image is only added if missing
            }

            if (Schema::hasColumn('about_us', 'history')) {
                $table->dropColumn('history'); // ❌ Remove old history JSON field if it exists
            }
        });
    }

    public function down()
    {
        Schema::table('about_us', function (Blueprint $table) {
            if (!Schema::hasColumn('about_us', 'history')) {
                $table->json('history')->nullable(); // ✅ Restore history JSON field if rolling back
            }

            $table->dropColumn(['history_title', 'history_description']);

            if (Schema::hasColumn('about_us', 'history_image')) {
                $table->dropColumn('history_image'); // ❌ Drop history_image only if it exists
            }
        });
    }
};
