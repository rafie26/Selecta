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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Superior', 'Deluxe', 'Suite'
            $table->text('description');
            $table->decimal('price_per_night', 10, 2);
            $table->integer('max_occupancy');
            $table->integer('total_rooms'); // Total number of rooms of this type
            $table->json('amenities')->nullable(); // Room amenities
            $table->json('images')->nullable(); // Room images
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
