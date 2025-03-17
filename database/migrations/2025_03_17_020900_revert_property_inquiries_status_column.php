<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('property_inquiries', function (Blueprint $table) {
            $table->string('status')->default('active')->change(); // ✅ Change back to string
        });
    }

    public function down() {
        Schema::table('property_inquiries', function (Blueprint $table) {
            $table->enum('status', ['active', 'unarchived', 'replied', 'archived'])->default('active')->change(); // ✅ Restore ENUM if needed
        });
    }
};
