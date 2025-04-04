<?php

// database/migrations/xxxx_xx_xx_create_testimonials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialsTable extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('experience');
            $table->tinyInteger('rating')->default(0);
            $table->string('photo')->nullable(); // self photo
            $table->json('media')->nullable(); // store multiple image/video URLs
            $table->boolean('status')->default(0); // 1 = featured, 0 = not featured
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
}

