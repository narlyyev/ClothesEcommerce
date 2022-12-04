<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Product $product) {
            //
        })->afterCreating(function (Product $product) {
            $product->save();

            $attributes = Attribute::with('values')
                ->orderBy('sort_order')
                ->get();

            $values = [];
            foreach ($attributes as $attribute) {
                $values[] = $attribute->values->random()->id;
            }

            $product->values()->sync($values);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = DB::table('users')->inRandomOrder()->first();
        $category = DB::table('categories')->inRandomOrder()->first();
        $brand = DB::table('brands')->inRandomOrder()->first();
        $nameTm = fake()->streetName();
        $nameEn = $nameTm;
        $fullNameTm = $brand->name . ' ' . $category->product_tm . ' ' . $nameTm;
        $fullNameEn = $brand->name . ' ' . $category->product_en . ' ' . $nameEn;
        $hasDiscount = fake()->boolean(20);
        return [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_tm' => $nameTm,
            'name_en' => $nameEn,
            'full_name_tm' => $fullNameTm,
            'full_name_en' => $fullNameEn,
            'barcode' => fake()->unique()->isbn13(),
            'description' => fake()->text(rand(300, 500)),
            'price' => fake()->randomFloat($nbMaxDecimals = 1, $min = 100, $max = 1000),
            'stock' => rand(0, 10),
            'discount_percent' => $hasDiscount
                ? rand(10, 20) : 0,
            'discount_start' => $hasDiscount
                ? Carbon::today()
                    ->subDays(rand(1, 7))
                    ->subHours(rand(1, 24))
                    ->subMinutes(rand(1, 60))
                    ->toDateTimeString()
                : Carbon::today()
                    ->startOfMonth()
                    ->toDateTimeString(),
            'discount_end' => $hasDiscount
                ? Carbon::today()
                    ->addDays(rand(1, 7))
                    ->addHours(rand(1, 24))
                    ->addMinutes(rand(1, 60))
                    ->toDateTimeString()
                : Carbon::today()
                    ->startOfMonth()
                    ->toDateTimeString(),
            'viewed' => rand(20, 100),
            'sold' => rand(5, 10),
            'favorites' => rand(0, 30),
            'random' => rand(0, 999),
        ];
    }
}
