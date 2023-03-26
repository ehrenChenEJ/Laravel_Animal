<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Animal;
use App\Models\Type;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // 取消Foreign key約束
        Schema::disableForeignKeyConstraints();
        Animal::truncate(); // 清空animal資料表 ID歸零
        User::truncate(); // 清空user資料表 ID歸零
        Type::truncate(); // 清空types資料表 ID歸零

        // 先產生Type資料
        Type::factory(5)->create();
        // 建立5筆會員測試資料
        User::factory(5)->create();
        // 建立1筆動物測試資料
        Animal::factory(10000)->create();
        // 開啟Foregin Key約束
        Schema::enableForeignKeyConstraints();
    }
}
