@extends('layouts.app')
@section('title')
    @lang('app.product') - @lang('app.app-name')
@endsection
@section('content')
    <div class="container-xl py-4">
        <div class="row justify-content-center">
            <div class="col-10 col-sm-8 col-md-6 col-lg-4">
                <div class="fs-4 fw-semibold text-center mb-3">
                    @lang('app.product')
                    <a href="{{ route('products.show', $obj->slug) }}" class="link-secondary">
                        <i class="bi-arrow-right-circle"></i>
                    </a>
                </div>

                <form action="{{ route('products.update', $obj->id) }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="mb-3">
                        <label for="category" class="form-label fw-semibold">
                            @lang('app.category')
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('category') is-invalid @enderror" name="category" id="category" required autofocus>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $obj->category_id == $category->id ? 'selected':'' }}>{{ $category->getName() }}</option>
                            @endforeach
                        </select>
                        @error('category')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="brand" class="form-label fw-semibold">
                            @lang('app.brand')
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('brand') is-invalid @enderror" name="brand" id="brand" required>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $obj->brand_id == $brand->id ? 'selected':'' }}>{{ $brand->getName() }}</option>
                            @endforeach
                        </select>
                        @error('brand')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    @foreach($attributes as $attribute)
                        <div class="bg-primary bg-opacity-10 rounded p-2 mb-3">
                            <label for="attribute{{ $attribute->id }}" class="form-label fw-semibold">
                                {{ $attribute->getName() }}
                            </label>
                            <select class="form-select @error('values') is-invalid @enderror" name="values[]" id="attribute{{ $attribute->id }}">
                                <option selected value="0">-</option>
                                @foreach($attribute->values as $value)
                                    <option value="{{ $value->id }}" {{ $obj->values->contains($value) ? 'selected="selected"' : '' }}>
                                        {{ $value->getName() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('values')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="mb-3">
                        <label for="name_tm" class="form-label fw-semibold">
                            <span class="text-danger">TM</span> @lang('app.name')
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name_tm') is-invalid @enderror" name="name_tm" id="name_tm" value="{{ $obj->name_tm }}" required>
                        @error('name_tm')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name_en" class="form-label fw-semibold">
                            <span class="text-danger">EN</span> @lang('app.name')
                        </label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en" id="name_en" value="{{ $obj->name_en }}">
                        @error('name_en')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="barcode" class="form-label fw-semibold">
                            @lang('app.barcode')
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi-upc-scan"></i></span>
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" name="barcode" id="barcode" value="{{ $obj->barcode }}">
                        </div>
                        @error('barcode')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">
                            @lang('app.description')
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="5" maxlength="1000">{{ $obj->description }}</textarea>
                        @error('description')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label fw-semibold">
                            @lang('app.price')
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" min="0" step="0.1" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{ $obj->price }}" required>
                            <span class="input-group-text fw-semibold">TMT</span>
                        </div>
                        @error('price')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label fw-semibold">
                            @lang('app.stock')
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" name="stock" id="stock" value="{{ $obj->stock }}" required>
                        @error('stock')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-semibold">
                            @lang('app.image')
                        </label>
                        <input type="file" accept="image/jpeg" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                        @error('image')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        @lang('app.update')
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection