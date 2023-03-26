<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        /**
         * 只給需要的欄位
         * 
         * 使用transform將每筆資料一筆筆處理
         * 官方文件: https://laravel.com/docs/8.x/collections#method-transform
         */
        return [
            'data' => $this->collection->transform(function ($type) {
                return [
                    'id'   => $type->id,
                    'name' => $type->name,
                    'sort' => $type->sort,
                ];
            })
        ];
    }
}
