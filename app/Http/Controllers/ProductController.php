<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'u' => 'nullable|array', // users
            'u.*' => 'nullable|integer|min:0|distinct',
            'c' => 'nullable|array', // categories
            'c.*' => 'nullable|integer|min:0|distinct',
            'b' => 'nullable|array', // brands
            'b.*' => 'nullable|integer|min:0|distinct',
            'v' => 'nullable|array', // values
            'v.*' => 'nullable|array',
            'v.*.*' => 'nullable|integer|min:0|distinct',
            'in_stock' => 'nullable|boolean',
            'has_discount' => 'nullable|boolean',
            'has_credit' => 'nullable|boolean',
        ]);
        $q = $request->q ?: null;
        $f_users = $request->has('u') ? $request->u : [];
        $f_categories = $request->has('c') ? $request->c : [];
        $f_brands = $request->has('b') ? $request->b : [];
        $f_values = $request->has('v') ? $request->v : [];
        $f_inStock = $request->has('in_stock') ? $request->in_stock : null;
        $f_hasDiscount = $request->has('has_discount') ? $request->has_discount : null;
        $f_hasCredit = $request->has('has_credit') ? $request->has_credit : null;

        $products = Product::when($q, function ($query, $q) {
            return $query->where(function ($query) use ($q) {
                $query->orWhere('full_name_tm', 'like', '%' . $q . '%');
                $query->orWhere('full_name_en', 'like', '%' . $q . '%');
                $query->orWhere('slug', 'like', '%' . $q . '%');
                $query->orWhere('barcode', 'like', '%' . $q . '%');
            });
        })
            ->when($f_users, function ($query, $f_users) {
                $query->whereIn('user_id', $f_users);
            })
            ->when($f_categories, function ($query, $f_categories) {
                $query->whereIn('category_id', $f_categories);
            })
            ->when($f_brands, function ($query, $f_brands) {
                $query->whereIn('brand_id', $f_brands);
            })
            ->when($f_values, function ($query, $f_values) {
                return $query->where(function ($query) use ($f_values) {
                    foreach ($f_values as $f_value) {
                        $query->whereHas('values', function ($query) use ($f_value) {
                            $query->whereIn('id', $f_value);
                        });
                    }
                });
            })
            ->when(isset($f_inStock), function ($query) {
                $query->where('stock', '>', 0);
            })
            ->when(isset($f_hasDiscount), function ($query) {
                return $query->where('discount_percent', '>', 0)
                    ->where('discount_start', '<=', Carbon::now()->toDateTimeString())
                    ->where('discount_end', '>=', Carbon::now()->toDateTimeString());
            })
            ->when(isset($f_hasCredit), function ($query) {
                $query->where('credit', 1);
            })
            ->with('user')
            ->orderBy('random')
            ->paginate(24);

        $products = $products->appends([
            'q' => $q,
            'u' => $f_users,
            'c' => $f_categories,
            'b' => $f_brands,
            'v' => $f_values,
            'in_stock' => $f_inStock,
            'has_discount' => $f_hasDiscount,
            'has_credit' => $f_hasCredit,
        ]);

        // FILTER
        $users = User::orderBy('name')
            ->get();
        $categories = Category::orderBy('sort_order')
            ->orderBy('slug')
            ->get();
        $brands = Brand::orderBy('slug')
            ->get();
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return view('product.index')
            ->with([
                'q' => $q,
                'f_users' => collect($f_users),
                'f_categories' => collect($f_categories),
                'f_brands' => collect($f_brands),
                'f_values' => collect($f_values)->collapse(),
                // collapse() method hemme value_lary bir arrayyn icine yerleshdiryar
                'f_inStock' => $f_inStock,
                'f_hasDiscount' => $f_hasDiscount,
                'f_hasCredit' => $f_hasCredit,
                'products' => $products,
                'users' => $users,
                'categories' => $categories,
                'brands' => $brands,
                'attributes' => $attributes,
            ]);
    }


    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with('user', 'category', 'brand', 'values.attribute')
            ->firstOrFail();

        if (Cookie::has('p_v')) {
            $productIds = explode(',', Cookie::get('p_v'));
            if (!in_array($product->id, $productIds)) {
                $product->increment('viewed');
                $productIds[] = $product->id;
                Cookie::queue('p_v', implode(',', $productIds), 60 * 8);
            }
        } else {
            $product->increment('viewed');
            Cookie::queue('p_v', $product->id, 60 * 8);
        }

        $category = Category::findOrFail($product->category_id);
        $products = Product::where('category_id', $category->id)
            ->with('user')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('product.show')
            ->with([
                'product' => $product,
                'category' => $category,
                'products' => $products,
            ]);
    }


    public function create()
    {
        $categories = Category::orderBy('sort_order')
            ->orderBy('slug')
            ->get();
        $brands = Brand::orderBy('slug')
            ->get();
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return view('product.create')
            ->with([
                'categories' => $categories,
                'brands' => $brands,
                'attributes' => $attributes,
            ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|integer|min:1',
            'brand' => 'required|integer|min:1',
            'values' => 'nullable|array',
            'values.*' => 'nullable|integer|min:0',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg|max:128|dimensions:width=1000,height=1000',
        ]);

        $category = Category::findOrFail($request->category);
        $brand = Brand::findOrFail($request->brand);

        $obj = Product::create([
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_tm' => $request->name_tm,
            'name_en' => $request->name_en ?: null,
            'full_name_tm' => $brand->name . ' ' . $category->product_tm . ' ' . $request->name_tm,
            'full_name_en' => $brand->name . ' ' . ($category->product_en ?: $category->product_tm) . ' ' . ($request->name_en ?: $request->name_tm),
            'barcode' => $request->barcode ?: null,
            'description' => $request->description ?: null,
            'price' => round($request->price, 1),
            'stock' => $request->stock,
        ]);
        $obj->save();
        $obj->values()->sync($request->has('values') ? array_filter($request->values) : []);

        if ($request->has('image')) {
            // generate name
            $name = str()->random(15) . '.jpg';
            // save normal
            Storage::putFileAs('public/products', $request->image, $name);
            // save small
            $imageSm = Image::make($request->image);
            $imageSm->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $imageSm = (string)$imageSm->encode('jpg', 90);
            Storage::put('public/products/sm/' . $name, $imageSm);
            // update obj
            $obj->image = $name;
            $obj->update();
        }

        return redirect()->back()
            ->with([
                'success' => 'Product (' . $obj->getFullName() . ') created!'
            ]);
    }


    public function edit($id)
    {
        $obj = Product::findOrFail($id);
        if (!$obj->isOwner()) {
            return abort(403);
        }

        $categories = Category::orderBy('sort_order')
            ->orderBy('slug')
            ->get();
        $brands = Brand::orderBy('slug')
            ->get();
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return view('product.edit')
            ->with([
                'obj' => $obj,
                'categories' => $categories,
                'brands' => $brands,
                'attributes' => $attributes,
            ]);
    }


    public function update(Request $request, $id)
    {
        $obj = Product::findOrFail($id);
        if (!$obj->isOwner()) {
            return abort(403);
        }

        $request->validate([
            'category' => 'required|integer|min:1',
            'brand' => 'required|integer|min:1',
            'values' => 'nullable|array',
            'values.*' => 'nullable|integer|min:0',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg|max:128|dimensions:width=1000,height=1000',
        ]);

        $category = Category::findOrFail($request->category);
        $brand = Brand::findOrFail($request->brand);

        $obj->category_id = $category->id;
        $obj->brand_id = $brand->id;
        $obj->name_tm = $request->name_tm;
        $obj->name_en = $request->name_en ?: null;
        $obj->full_name_tm = $brand->name . ' ' . $category->product_tm . ' ' . $request->name_tm;
        $obj->full_name_en = $brand->name . ' ' . ($category->product_en ?: $category->product_tm) . ' ' . ($request->name_en ?: $request->name_tm);
        $obj->barcode = $request->barcode ?: null;
        $obj->description = $request->description ?: null;
        $obj->price = round($request->price, 1);
        $obj->stock = $request->stock;
        $obj->update();
        $obj->values()->sync($request->has('values') ? array_filter($request->values) : []);

        if ($request->has('image')) {
            if ($obj->image) {
                Storage::delete('public/products/' . $obj->image);
                Storage::delete('public/products/sm/' . $obj->image);
            }
            // generate name
            $name = str()->random(15) . '.jpg';
            // save normal
            Storage::putFileAs('public/products', $request->image, $name);
            // save small
            $imageSm = Image::make($request->image);
            $imageSm->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $imageSm = (string)$imageSm->encode('jpg', 90);
            Storage::put('public/products/sm/' . $name, $imageSm);
            // update obj
            $obj->image = $name;
            $obj->update();
        }

        return redirect()->back()
            ->with([
                'success' => 'Product (' . $obj->getFullName() . ') updated!'
            ]);
    }


    public function delete($id)
    {
        $obj = Product::findOrFail($id);
        if (!$obj->isOwner()) {
            return abort(403);
        }

        if ($obj->image) {
            Storage::delete('public/products/' . $obj->image);
            Storage::delete('public/products/sm/' . $obj->image);
        }
        $objName = $obj->getFullName();
        $obj->delete();

        return redirect()->route('home')
            ->with([
                'success' => 'Product (' . $objName . ') deleted!'
            ]);
    }
}
