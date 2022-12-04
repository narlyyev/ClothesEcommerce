<nav class="navbar navbar-expand-md navbar-dark text-bg-primary" aria-label="navbar">
    <div class="container-xl">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="bi-cart4"></i> @lang('app.app-name')</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbars">
            <form action="{{ route('products.index') }}" class="px-2" role="search">
                <input class="form-control" type="search" name="q" value="{{ isset($q) ? $q : old('q') }}" placeholder="@lang('app.search')" aria-label="Search">
            </form>
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        @lang('app.categories')
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.show', $category->slug) }}">
                                    {{ $category->getName() }}
                                    <span class="badge text-bg-primary bg-opacity-75">{{ $category->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contacts.create') }}">
                        <i class="bi-envelope-plus"></i> @lang('app.contact')
                    </a>
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-plus-circle"></i> @lang('app.add')
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('products.create') }}">
                                    @lang('app.product')
                                </a>
                            </li>
                            @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('categories.create') }}">
                                        @lang('app.category')
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('brands.create') }}">
                                        @lang('app.brand')
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                            <i class="bi-box-arrow-right"></i> {{ auth()->user()->name }}
                        </a>
                    </li>
                    <form id="logoutForm" action="{{ route('logout') }}" method="post" class="d-none">
                        @csrf
                    </form>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="bi-person-plus"></i> @lang('app.register')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi-box-arrow-in-right"></i> @lang('app.login')
                        </a>
                    </li>
                @endauth
                @if(app()->getLocale() == 'en')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('language', 'tm') }}">
                            <img src="{{ asset('storage/img/flag/tm.svg') }}" alt="Türkmen" style="height:1rem;">
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('language', 'en') }}">
                            <img src="{{ asset('storage/img/flag/en.svg') }}" alt="English" style="height:1rem;">
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@auth
@endauth