<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Step 1: Convert ENUM to TEXT temporarily
            $table->text('type_of_listing')->change();
        });

        // Step 2: Convert existing ENUM values to JSON format
        DB::statement("UPDATE properties SET type_of_listing = JSON_ARRAY(type_of_listing) WHERE type_of_listing IS NOT NULL");

        Schema::table('properties', function (Blueprint $table) {
            // Step 3: Convert TEXT to JSON
            $table->json('type_of_listing')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Step 1: Convert JSON back to TEXT
            $table->text('type_of_listing')->change();
        });

        // Step 2: Convert JSON back to ENUM (if needed)
        DB::statement("UPDATE properties SET type_of_listing = JSON_UNQUOTE(type_of_listing)");

        Schema::table('properties', function (Blueprint $table) {
            // Step 3: Convert TEXT back to ENUM (WARNING: This might fail if invalid data exists)
            $table->enum('type_of_listing', ['For Rent', 'For Sale'])->change();
        });
    }
};
