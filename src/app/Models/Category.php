<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Item::class, 'item_categories', 'category_id', 'item_id');
    }
}
