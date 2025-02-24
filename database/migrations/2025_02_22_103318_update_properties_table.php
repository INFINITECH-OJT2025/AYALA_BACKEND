<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Personal Info
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('email')->after('last_name');
            $table->string('phone_number')->after('email');

            // Type of Listing
            $table->enum('type_of_listing', ['For rent', 'For sale'])->after('phone_number');

            // Property Info
            $table->string('property_name')->after('type_of_listing');
            $table->enum('unit_type', ['Studio type', '1BR', '2BR', 'Loft', 'Penthouse'])->after('property_name');
            $table->enum('unit_status', ['Bare', 'Semi-Furnished', 'Fully Furnished', 'Interiored'])->after('unit_type');
            $table->integer('square_meter')->after('price');
            $table->integer('floor_number')->after('square_meter');
            $table->enum('parking', ['With', 'Without'])->after('floor_number');

            // Features and Amenities (Boolean Fields)
            $table->boolean('pool_area')->default(false)->after('parking');
            $table->boolean('guest_suite')->default(false)->after('pool_area');
            $table->boolean('underground_parking')->default(false)->after('guest_suite');
            $table->boolean('pet_friendly_facilities')->default(false)->after('underground_parking');
            $table->boolean('balcony_terrace')->default(false)->after('pet_friendly_facilities');
            $table->boolean('club_house')->default(false)->after('balcony_terrace');
            $table->boolean('gym_fitness_center')->default(false)->after('club_house');
            $table->boolean('elevator')->default(false)->after('gym_fitness_center');
            $table->boolean('concierge_services')->default(false)->after('elevator');
            $table->boolean('security')->default(false)->after('concierge_services');

            // Property Image
            $table->string('property_image')->nullable()->after('security');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'email', 'phone_number',
                'type_of_listing', 'property_name', 'unit_type', 'unit_status',
                'square_meter', 'floor_number', 'parking',
                'pool_area', 'guest_suite', 'underground_parking',
                'pet_friendly_facilities', 'balcony_terrace', 'club_house',
                'gym_fitness_center', 'elevator', 'concierge_services', 'security',
                'property_image'
            ]);
        });
    }
};
