<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    // 定義哪些欄位可以被批量賦值
    protected $fillable = [
        'category_id',
        'name',
        'status',
    ];

    // 定義與 Categories 表的關聯
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted()
    {
        static::created(function ($subCategory) {
            Redis::del('categories');
        });

        static::updated(function ($subCategory) {
            Redis::del('categories');
        });

        static::deleted(function ($subCategory) {
            Redis::del('categories');
        });
    }
}