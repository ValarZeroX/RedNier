<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Categories Model
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function communities()
    {
        return $this->hasMany(Community::class);
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
