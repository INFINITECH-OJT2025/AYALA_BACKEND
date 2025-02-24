<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'buyer', 'seller'])->default('buyer');
            $table->boolean('is_approved')->default(false); // Only for buyers
            $table->timestamps();
        });

        // Insert the predefined admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'anyayahanjosedexter@gmail.com',
            'password' => Hash::make('ayala2025'),
            'role' => 'admin',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
