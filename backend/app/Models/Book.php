<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'total_pages', 'genre_id'];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function readers()
    {
        return $this->hasMany(UserBook::class);
    }
}
