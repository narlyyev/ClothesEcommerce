<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        return view('brand.create');
    }


    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('brands')],
            'image' => ['nullable', 'image', 'mimes:png', 'max:16', 'dimensions:width=120,height=120'],
        ]);

        $obj = Brand::create([
            'name' => $request->name,
        ]);

        if ($request->has('image')) {
            $name = str()->random(10) . '.png';
            Storage::putFileAs('public/brands', $request->image, $name);
            $obj->image = $name;
            $obj->update();
        }

        return redirect()->back()
            ->with([
                'success' => 'Brand created!'
            ]);
    }


    public function edit($id)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $obj = Brand::findOrFail($id);

        return view('brand.edit')
            ->with([
                'obj' => $obj,
            ]);
    }


    public function update(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $obj = Brand::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('brands')->ignore($obj->id)],
            'image' => ['nullable', 'image', 'mimes:png', 'max:16', 'dimensions:width=120,height=120'],
        ]);

        $obj->name = $request->name;
        $obj->update();

        if ($request->has('image')) {
            if ($obj->image) {
                Storage::delete('public/brands/' . $obj->image);
            }
            $name = str()->random(10) . '.png';
            Storage::putFileAs('public/brands', $request->image, $name);
            $obj->image = $name;
            $obj->update();
        }

        return redirect()->back()
            ->with([
                'success' => 'Brand updated!'
            ]);
    }
}
