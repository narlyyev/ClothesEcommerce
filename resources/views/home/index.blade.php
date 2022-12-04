@extends('layouts.app')
@section('title')
    @lang('app.app-name')
@endsection

@section('content')

    @foreach($categoryProducts as $categoryProduct)
        @if($categoryProduct['products']->count() > 0)
            @include('home.categories')
        @endif
    @endforeach

@endsection