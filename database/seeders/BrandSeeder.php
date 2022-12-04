<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objs = [
            'Nike',
            'Adidas',
            'Puma',
            'Mavi',
            'Cotton',
            'Defacto',
            'LC Waikiki',
            'Under Armour',
            'Bershka',
            'Pull & Bear',
            'Avva',
            'Reebok',
            'Lacoste',
            'Mango',
        ];
        foreach ($objs as $obj) {
            Brand::create([
               'name' => $obj,
            ]);
        }
    }
}
