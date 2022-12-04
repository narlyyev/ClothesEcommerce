<form action="{{ url()->current() }}">
    <input type="hidden" name="q" value="{{ isset($q) ? $q : old('q') }}">
    <div class="accordion" id="accordion">
        <div class="accordion-item">
            <button class="accordion-button" type="button" id="panels-h1" data-bs-toggle="collapse" data-bs-target="#panels-c1" aria-expanded="true" aria-controls="panels-c1">
                @lang('app.users')
            </button>
            <div id="panels-c1" class="accordion-collapse collapse show" aria-labelledby="panels-h1">
                <div class="accordion-body p-1">
                    @foreach($users as $user)
                        <div class="form-check m-2">
                            <input class="form-check-input" type="checkbox" id="u{{ $user->id }}" name="u[]"
                                   value="{{ $user->id }}" {{ $f_users->contains($user->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="u{{ $user->id }}">
                                {{ $user->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-button collapsed" type="button" id="panels-h2" data-bs-toggle="collapse" data-bs-target="#panels-c2" aria-expanded="false" aria-controls="panels-c2">
                @lang('app.categories')
            </button>
            <div id="panels-c2" class="accordion-collapse collapse" aria-labelledby="panels-h2">
                <div class="accordion-body p-1">
                    @foreach($categories as $category)
                        <div class="form-check m-2">
                            <input class="form-check-input" type="checkbox" id="c{{ $category->id }}" name="c[]"
                                   value="{{ $category->id }}" {{ $f_categories->contains($category->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="c{{ $category->id }}">
                                {{ $category->getName() }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-button collapsed" type="button" id="panels-h3" data-bs-toggle="collapse" data-bs-target="#panels-c3" aria-expanded="false" aria-controls="panels-c3">
                @lang('app.brands')
            </button>
            <div id="panels-c3" class="accordion-collapse collapse" aria-labelledby="panels-h3">
                <div class="accordion-body p-1">
                    @foreach($brands as $brand)
                        <div class="form-check m-2">
                            <input class="form-check-input" type="checkbox" id="b{{ $brand->id }}" name="b[]"
                                   value="{{ $brand->id }}" {{ $f_brands->contains($brand->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="b{{ $brand->id }}">
                                {{ $brand->getName() }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @foreach($attributes as $attribute)
            <div class="accordion-item">
                <button class="accordion-button collapsed" type="button" id="panels-ha{{ $attribute->id }}" data-bs-toggle="collapse" data-bs-target="#panels-ca{{ $attribute->id }}" aria-expanded="false" aria-controls="panels-ca{{ $attribute->id }}">
                    {{ $attribute->getName() }}
                    <span class="d-none">{{ $i = $loop->index }}</span>
                </button>
                <div id="panels-ca{{ $attribute->id }}" class="accordion-collapse collapse" aria-labelledby="panels-ha{{ $attribute->id }}">
                    <div class="accordion-body p-1">
                        @foreach($attribute->values as $value)
                            <div class="form-check m-2">
                                <input class="form-check-input" type="checkbox" id="a{{ $value->id }}" name="v[{{ $i }}][]"
                                       value="{{ $value->id }}" {{ $f_values->contains($value->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="a{{ $value->id }}">
                                    {{ $value->getName() }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        <div class="accordion-item">
            <button class="accordion-button collapsed" type="button" id="panels-h4" data-bs-toggle="collapse" data-bs-target="#panels-c4" aria-expanded="false" aria-controls="panels-c4">
                @lang('app.search')
            </button>
            <div id="panels-c4" class="accordion-collapse collapse" aria-labelledby="panels-h4">
                <div class="accordion-body p-1">
                    <div class="form-check m-2">
                        <input class="form-check-input" type="checkbox" id="inStock" name="in_stock" value="1" {{ $f_inStock ? 'checked' : '' }}>
                        <label class="form-check-label" for="inStock">
                            @lang('app.inStock')
                        </label>
                    </div>
                    <div class="form-check m-2">
                        <input class="form-check-input" type="checkbox" id="hasDiscount" name="has_discount" value="1" {{ $f_hasDiscount ? 'checked' : '' }}>
                        <label class="form-check-label" for="hasDiscount">
                            @lang('app.hasDiscount')
                        </label>
                    </div>
                    <div class="form-check m-2">
                        <input class="form-check-input" type="checkbox" id="hasCredit" name="has_credit" value="1" {{ $f_hasCredit ? 'checked' : '' }}>
                        <label class="form-check-label" for="hasCredit">
                            @lang('app.hasCredit')
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center">
        <div class="col-6">
            <button type="submit" class="btn btn-primary btn-sm w-100 my-2">
                <i class="bi-filter"></i>
            </button>
        </div>
        <div class="col-6">
            <a href="{{ url()->current() }}" class="btn btn-light btn-sm w-100">
                <i class="bi-trash3-fill"></i>
            </a>
        </div>
    </div>

</form>