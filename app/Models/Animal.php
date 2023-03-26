<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon; // 操作時間的套件

class Animal extends Model
{
    use HasFactory;

    /**
     * 可批量寫入的屬性
     * 
     * 覆寫父類別屬性
     * 限制哪些欄位可以被批量寫入（安全性問題）
     * @var array
     */
    protected $fillable = [
        'type_id',
        'name',
        'birthday',
        'area',
        'fix',
        'description',
        'personality',
        'user_id', // 不建議允許批量寫入，後續再更動
    ];

    /**
     * 取得動物分類
     * 
     * 命名用單數，因為一個動物只會有一種分類
     */
    public function type()
    {
        // belongsTo(類別名稱,參照欄位,主鍵)
        // 因為使用laravel預設的type_id主鍵預設為id，所以後面可以省略
        return $this->belongsTo('App\Models\Type');
    }

    /**
     * get[]Attribute的命名可以讓$animal->age連結上
     * $animal->age會自動執行這個function
     */
    public function getAgeAttribute()
    {
        $diff = Carbon::now()->diff($this->birthday);

        return "{$diff->y}歲{$diff->m}月";
    }
}
