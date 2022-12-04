<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objs = [
            ['Ýakaly köýnekler', 'Shirts', 'Ýakaly köýnek', 'Shirt', 1],
            ['Köýnekler', 'Dresses', 'Köýnek', 'Dress', 1],
            [ 'Tolstowkalar', 'Hoodies', 'Tolstowka', 'Hoodie', 1],
            ['Şortiklar', 'Shorts', 'Şortik', 'Short', 1],
            ['Galstuklar', 'Ties', 'Galstuk', 'Tie', 1],
        ];
        for ($i = 0; $i < count($objs); $i++) {
            Category::create([
               'name_tm' => $objs[$i][0],
               'name_en' => $objs[$i][1],
               'product_tm' => $objs[$i][2],
               'product_en' => $objs[$i][3],
               'home' => $objs[$i][4],
               'sort_order' => $i + 1,
            ]);
        }
    }
}
