<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Topic Model
 */
class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'community_id', 'title', 'content', 'views', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}