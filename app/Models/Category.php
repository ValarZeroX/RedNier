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

    //在 Category 模型中，可以使用 Laravel 提供的模型事件來監聽 created、updated 和 deleted 事件，並在資料變動時更新或刪除 Redis 緩存。
    protected static function booted()
    {
        static::created(function ($category) {
            // 清除緩存，觸發重新加載
            Redis::del('categories');
        });

        static::updated(function ($category) {
            // 清除緩存，觸發重新加載
            Redis::del('categories');
        });

        static::deleted(function ($category) {
            // 清除緩存，觸發重新加載
            Redis::del('categories');
        });
    }
}
