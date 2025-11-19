<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

 class MenuItem extends Model
 {
     use HasFactory;

     /**
      * The attributes that are mass assignable.
      *
      * @var array<int, string>
      */
     protected $fillable = [
         'restaurant_id',
         'name',
         'description',
         'image_path',
         'category',
         'price',
         'is_active',
         'sort_order',
     ];

     /**
      * The attributes that should be cast.
      *
      * @var array<string, string>
      */
     protected $casts = [
         'price' => 'decimal:0',
         'is_active' => 'boolean',
     ];

     protected $appends = [
        'image_url',
        'formatted_price',
    ];

     /**
      * Get the restaurant that owns the menu item.
      */
     public function restaurant()
     {
         return $this->belongsTo(Restaurant::class);
     }

     /**
      * Accessor for menu item image URL.
      */
     public function getImageUrlAttribute(): ?string
     {
         if (!$this->image_path) {
             return null;
         }

         if (Str::startsWith($this->image_path, ['http://', 'https://', '/'])) {
             return $this->image_path;
         }

         return asset('storage/' . ltrim($this->image_path, '/'));
     }

     /**
      * Accessor for formatted price.
      */
     public function getFormattedPriceAttribute(): string
     {
         return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
     }
 }

