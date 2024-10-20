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

    // 其他模型方法可以根據需求添加，例如 Scope 查詢等
}