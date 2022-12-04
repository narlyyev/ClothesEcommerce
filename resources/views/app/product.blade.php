<div class="position-relative bg-white border rounded">
    <div class="row g-0">
        <div class="col-6">
            <div class="d-flex">
                <img src="{{  $product->image ? Storage::url('products/sm/' . $product->image) : asset('storage/img/sm/product.jpg') }}"
                     alt="{{ $product->getFullName() }}" class="img-fluid rounded-start">
                <div class="position-absolute">
                    @if($product->isDiscount())
                        <div class="m-1">
                            <span class="d-inline-block small text-bg-danger bg-opacity-75 rounded px-1">
                                <i class="bi-check2-square"></i> @lang('app.discount')
                            </span>
                        </div>
                    @endif
                    @if($product->credit)
                        <div class="m-1">
                            <span class="d-inline-block small text-bg-success bg-opacity-75 rounded px-1">
                                <i class="bi-check2-square"></i> @lang('app.credit')
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col p-2">
            <div class="d-flex flex-column h-100">
                <div class="fw-semibold mb-auto">
                    <a href="{{ route('products.show', $product->slug) }}" class="link-dark text-decoration-none stretched-link">
                        {{ $product->getFullName() }}
                    </a>
                </div>
                @if($product->isDiscount())
                    <div class="small text-secondary text-decoration-line-through">
                        {{ number_format($product->price, 2, '.', ' ') }}
                        <small>TMT</small>
                    </div>
                    <div class="fw-semibold text-danger">
                        {{ number_format($product->getPrice(), 2, '.', ' ') }}
                        <small>TMT</small>
                    </div>
                @else
                    <div class="fw-semibold text-primary">
                        {{ number_format($product->price, 2, '.', ' ') }}
                        <small>TMT</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>