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

    protected $fillable = ['name', 'sub_categories_id', 'status', 'description'];

    // 關聯至 SubCategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Community 與 Topic 的關聯
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}