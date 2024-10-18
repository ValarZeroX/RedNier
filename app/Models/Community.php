<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Communities Model
 */
class Community extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}