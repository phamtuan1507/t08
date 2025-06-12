<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        Menu::create(['name' => 'Trang chủ', 'slug' => 'trang-chu', 'url' => '/', 'order' => 1]);
        Menu::create(['name' => 'Sản phẩm', 'slug' => 'san-pham', 'url' => '/products', 'order' => 2]);
        Menu::create(['name' => 'Giới thiệu', 'slug' => 'gioi-thieu', 'url' => '/about', 'order' => 3]);
        Menu::create(['name' => 'Liên hệ', 'slug' => 'lien-he', 'url' => '/contact', 'order' => 4]);
        Menu::create(['name' => 'Tin tức', 'slug' => 'tin-tuc', 'url' => '/news', 'order' => 5]);
    }
}
