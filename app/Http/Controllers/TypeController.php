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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        return response(['data' => $type, Response::HTTP_CREATED]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        //
    }
}
