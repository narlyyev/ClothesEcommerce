<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categoryProducts = [];
        $categories = Category::where('home', 1)
            ->orderBy('sort_order')
            ->orderBy('slug')
            ->get();

        foreach ($categories as $category) {
            $categoryProducts[] = [
                'category' => $category,
                'products' => Product::where('category_id', $category->id)
                    ->with('user')
                    ->inRandomOrder()
                    ->take(6)
                    ->get(),
            ];
        }

        return view('home.index')
            ->with([
                'categoryProducts' => collect($categoryProducts),
            ]);
    }


    public function language($locale)
    {
        switch ($locale) {
            case 'tm':
                session()->put('locale', 'tm');
                return redirect()->back();
                break;
            case 'en':
                session()->put('locale', 'en');
                return redirect()->back();
                break;
            default:
                return redirect()->back();
        }
    }
}
