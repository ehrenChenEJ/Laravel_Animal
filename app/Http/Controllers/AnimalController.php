<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 設定預設值
        $limit = $request->limit ?? 10; // 未設定預設值為10

        // 使用Model orderBy方法加入SQL語法排序條件，依照id由大到小排序
        $animals = Animal::orderBy('id', 'desc')
            ->paginate($limit) // 使用分頁方法，最多回傳$limit比資料，自動包一個data的key值以及其他分頁的資訊
            ->appends($request->query());
        // appends => 將使用者請求的參數附加在分頁資訊中

        return response($animals, Response::HTTP_OK);
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
        $animal = Animal::create($request->all());
        $animal = $animal->refresh(); // 回傳欄位該筆完整資料

        // 將資料回傳，設定HTTP代碼
        return response($animal, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Animal $animal)
    {
        return response($animal, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Animal $animal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Animal $animal)
    {
        // put通常是替換掉舊資料
        // $request 使用者輸入資料
        // 動物資料ID
        $animal->update($request->all());
        return response($animal, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Animal $animal)
    {
        // 變數名稱必須對應到路由 api/animals/{animal}
        // “隱形模式綁定”
        $animal->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
