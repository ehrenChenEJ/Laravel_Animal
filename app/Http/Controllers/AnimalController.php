<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 使用網址設定為快取檔案名稱
        // get url
        $url = $request->url();

        // get query params
        $queryParams = $request->query();

        // 每個人請求的參數會不一樣，使用參數第一個英文字排序
        ksort($queryParams);

        // 利用http_build_query方法將查詢參數轉為字串
        $queryString = http_build_query($queryParams);

        // 組成完整網址
        $fullUrl = "{$url}?{$queryString}";

        // 使用laravel查詢是否有快取紀錄
        if (Cache::has($fullUrl)) {
            // 使用return 直接回傳快取資料，不作其他程式邏輯
            return Cache::get($fullUrl);
        }

        // 設定預設值
        $limit = $request->limit ?? 10; // 未設定預設值為10
        $query = Animal::query();

        // 篩選邏輯 如果有設定filter參數
        if (isset($request->filters)) {
            $filters = explode(',', $request->filters);
            foreach ($filters as $key => $filter) {
                list($key, $value) = explode(":", $filter);
                $query->where($key, 'like', "%$value%"); // (欄位名稱,比對條件＿省略的話就是要完全一樣,要查詢比對的字串)
                // orWhere任一條件成立
            }
        }

        // 列表排序
        if (isset($request->sorts)) {
            $sorts = explode(',', $request->sorts);

            foreach ($sorts as $key => $sort) {
                list($key, $value) = explode(':', $sort);
                if ($value == 'asc' || $value == 'desc') {
                    $query->orderBy($key, $value);
                } else {
                    $query->orderBy('id', 'desc');
                }
            }
        }

        // 使用Model orderBy方法加入SQL語法排序條件，依照id由大到小排序
        $animals = $query
            ->paginate($limit)
            ->appends($request->query());

        // 沒有快取紀錄的話記住資料，設定60秒過期，快取名稱使用網址命名
        return Cache::remember($fullUrl, 60, function () use ($animals) {
            return response($animals, Response::HTTP_OK);
        });
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
        // 表單驗證
        $this->validate($request, [
            'type_id'  => 'nullable|integer',
            'name'     => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'area'     => 'nullable|string|max:255',
            'fix'      => 'required|boolean',
            'description' => 'nullable',
            'personality' => 'nullable'
        ]);

        // 後面登入驗證再改
        $request['user_id'] = 1;

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
        // 表單驗證
        $this->validate($request, [
            'type_id'  => 'nullable|integer',
            'name'     => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'area'     => 'nullable|string|max:255',
            'fix'      => 'required|boolean',
            'description' => 'nullable',
            'personality' => 'nullable'
        ]);

        // 後面登入驗證再改
        $request['user_id'] = 1;

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
