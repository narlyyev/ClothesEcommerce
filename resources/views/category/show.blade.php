@extends('layouts.app')
@section('title')
    {{ $category->getName() }} - @lang('app.app-name')
@endsection
@section('content')
    <div class="container-xl py-4">
        <div class="fs-4 fw-semibold mb-3">
            {{ $category->getName() }}
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xxl-4 g-4 mb-4">
            @foreach($products as $product)
                <div class="col">
                    @include('app.product')
                </div>
            @endforeach
        </div>
        {{ $products->links() }}
    </div>
@endsection