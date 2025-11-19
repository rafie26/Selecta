<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\MenuItem;

 class Restaurant extends Model
 {
     use HasFactory;

     /**
      * The attributes that are mass assignable.
      *
      * @var array<int, string>
      */
     protected $fillable = [
         'name',
         'slug',
         'description',
         'image_path',
         'cuisine_type',
         'features',
         'operating_hours',
         'location',
         'is_active',
     ];

     /**
      * The attributes that should be cast.
      *
      * @var array<string, string>
      */
     protected $casts = [
         'features' => 'array',
         'is_active' => 'boolean',
     ];

     protected $appends = [
        'image_url',
    ];

     /**
      * Get all menu items for the restaurant.
      */
     public function menuItems()
     {
         return $this->hasMany(MenuItem::class);
     }

     /**
      * Get only active menu items ordered by sort_order.
      */
     public function activeMenuItems()
     {
         return $this->menuItems()
             ->where('is_active', 1)
             ->orderBy('sort_order');
     }

     /**
      * Accessor for restaurant image URL.
      */
     public function getImageUrlAttribute(): ?string
     {
         if (!$this->image_path) {
             return null;
         }

         // If already a full or root-relative URL, return as is
         if (Str::startsWith($this->image_path, ['http://', 'https://', '/'])) {
             return $this->image_path;
         }

         return asset('storage/' . ltrim($this->image_path, '/'));
     }
 }

