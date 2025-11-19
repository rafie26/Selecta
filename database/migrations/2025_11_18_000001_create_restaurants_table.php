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
         Schema::create('restaurants', function (Blueprint $table) {
             $table->id();
             $table->string('name');
             $table->string('slug')->unique();
             $table->text('description')->nullable();
             $table->string('image_path')->nullable();
             $table->string('cuisine_type')->nullable();
             $table->json('features')->nullable();
             $table->string('operating_hours')->nullable();
             $table->string('location')->nullable();
             $table->boolean('is_active')->default(true);
             $table->timestamps();
         });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
         Schema::dropIfExists('restaurants');
     }
 };

