<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objs = [
            ['name_tm' => 'Reňk', 'name_en' => 'Color', 'values' => [
                ['name_tm' => 'Ak', 'name_en' => 'White'],
                ['name_tm' => 'Gara', 'name_en' => 'Black'],
                ['name_tm' => 'Çal', 'name_en' => 'Gray'],
                ['name_tm' => 'Gyzyl', 'name_en' => 'Red'],
                ['name_tm' => 'Ýaşyl', 'name_en' => 'Green'],
                ['name_tm' => 'Gök', 'name_en' => 'Blue'],
            ]],
            ['name_tm' => 'Ölçeg', 'name_en' => 'Size', 'values' => [
                ['name_tm' => 'S', 'name_en' => null],
                ['name_tm' => 'M', 'name_en' => null],
                ['name_tm' => 'L', 'name_en' => null],
                ['name_tm' => 'XL', 'name_en' => null],
                ['name_tm' => 'XXL', 'name_en' => null],
                ['name_tm' => 'XXXL', 'name_en' => null],
                ['name_tm' => 'XXXXL', 'name_en' => null],
            ]],
            ['name_tm' => 'Görnüş', 'name_en' => 'Material', 'values' => [
                ['name_tm' => 'Ýüpek', 'name_en' => 'Silk',],
                ['name_tm' => 'Pagta', 'name_en' => 'Cotton',],
                ['name_tm' => 'Ýüň', 'name_en' => 'Wool',],
                ['name_tm' => 'Deri', 'name_en' => 'Leather',],
                ['name_tm' => 'Sintetik', 'name_en' => 'Synthetic',],
            ]],
            [
                'name_tm' => 'Jyns', 'name_en' => 'Sex', 'values' => [
                    ['name_tm' => 'Erkek', 'name_en' => 'Male'],
                    ['name_tm' => 'Aýal', 'name_en' => 'Female'],
            ]],
        ];
        for ($i = 0; $i < count($objs); $i++) {
            $attribute = Attribute::create([
               'name_tm' => $objs[$i]['name_tm'],
               'name_en' => $objs[$i]['name_en'],
                'sort_order' => $i + 1,
            ]);
            for ($j = 0; $j < count($objs[$i]['values']); $j++) {
                AttributeValue::create([
                   'attribute_id' => $attribute->id,
                   'name_tm' => $objs[$i]['values'][$j]['name_tm'],
                   'name_en' => $objs[$i]['values'][$j]['name_en'],
                   'sort_order' => $j + 1,
                ]);
            }
        }
    }
}
