<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->json('history')->nullable()->change(); // ✅ Allow multiple history items
        });
    }
    
    public function down()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->text('history')->nullable()->change();
        });
    }
    
};
