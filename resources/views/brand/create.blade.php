@extends('layouts.app')
@section('title')
    @lang('app.brand') - @lang('app.app-name')
@endsection
@section('content')
    <div class="container-xl py-4">
        <div class="row justify-content-center">
            <div class="col-10 col-sm-8 col-md-6 col-lg-4">
                <div class="fs-4 fw-semibold text-center mb-3">
                    @lang('app.brand')
                </div>

                <form action="{{ route('brands.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            @lang('app.name')
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-semibold">
                            @lang('app.image')
                        </label>
                        <input type="file" accept="image/png" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                        @error('image')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        @lang('app.save')
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection