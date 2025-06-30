<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'item_id', 'status'];

    public function item()
    {
        return $this->belongTo(Item::class);
    }

    public function buyer()
    {
        return $this->belongTo(User::class, 'buyer_id');
    }
}
