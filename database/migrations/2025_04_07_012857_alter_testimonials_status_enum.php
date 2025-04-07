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
    Schema::table('testimonials', function (Blueprint $table) {
        $table->enum('status', ['unpublished', 'published'])->default('unpublished')->change();
    });
}

public function down()
{
    Schema::table('testimonials', function (Blueprint $table) {
        $table->integer('status')->default(0)->change();
    });
}

};
