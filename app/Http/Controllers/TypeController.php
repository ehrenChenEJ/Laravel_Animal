<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 分類少量，可以全輸出
        $types = Type::get();

        return response([
            'data' => $types
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 另外一種寫法
        $this->validate($request, [
            'name' => [
                'required',
                'max:50',
                // type表中 name要是唯一值
                Rule::unique('types', 'name')
            ],
            'sort' => [
                'nullable',
                'integer'
            ],
        ]);

        // 如果沒有傳入欄位內容
        if (!isset($request->sort)) {
            $max = Type::max('sort'); // 找目前資料表的最大值
            $request['sort'] = $max + 1;
        }

        $type =  Type::create($request->all()); // 寫入資料庫

        return response(['data' => $type], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        return response(['data' => $type], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        $this->validate($request, [
            'name' => [
                'max:50',
                // 更新時排除自己的名字檢查是否是唯一值
                Rule::unique('types', 'name')->ignore($type->name, 'name')
            ],
            'sort' => [
                'nullable',
                'integer'
            ]
        ]);

        // 更新資料庫
        $type->update($request->all());

        return response(['data' => $type], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $type->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
