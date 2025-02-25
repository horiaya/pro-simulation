<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_categories', 'item_id', 'category_id');
    }

    public function myLists()
    {
        return $this->hasMany(MyList::class);
    }

    public function myListedUsers()
    {
        return $this->belongsToMany(User::class, 'my_lists', 'item_id', 'user_id');
    }

    public function scopeSearch(Builder $query, $keyword)
    {
        if (!empty($keyword)) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('item_name', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }
}
