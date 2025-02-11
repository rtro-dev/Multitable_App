@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::guest())
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif

                                    <br><br>
                                    <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="fas fa-store me-2"></i>Available Products</h2>
                    <div class="d-flex gap-3">
                        @if(Auth::user() != null)
                            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>New Product
                            </a>
                            <a href="{{ route('sales.user', ['user' => Auth::id()]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-box me-2"></i>My Products
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(session('success') || session('error'))
            <div class="row justify-content-center">
                <div class="col-md-10">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            @foreach($sales as $sale)
                <div class="col-md-5 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="position-relative">
                            <div style="height: 300px; background-color: #f8f9fa;">
                                @if($sale->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $sale->images->firstWhere('is_main', true)->route ?? $sale->images->first()->route) }}"
                                        class="h-100 w-100"
                                        style="object-fit: contain;"
                                        alt="{{ $sale->product }}">
                                @else
                                    <div class="h-100 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('images/basica.png') }}"
                                            class="w-50"
                                            style="object-fit: contain;"
                                            alt="Imagen por defecto">
                                    </div>
                                @endif
                            </div>
                            
                            @if($sale->isSold)
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0,0,0,0.5);">
                                <span class="text-white" style="font-size: 3rem; font-weight: bold; letter-spacing: 0.2rem;">SOLD</span>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">{{ $sale->product }}</h5>
                            <p class="text-muted mb-2">{{ Str::limit($sale->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary fs-6">{{ $sale->category->name }}</span>
                                <h5 class="text-success mb-0">{{ number_format($sale->price, 2, ',', '.') }}€</h5>
                            </div>
                            
                            <div class="text-muted small mb-3">
                                <i class="fas fa-user me-2"></i>{{ $sale->user->name }}
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#showModal-{{ $sale->id }}">
                                    <i class="fas fa-info-circle me-2"></i>Info
                                </button>
                                
                                @if(Auth::id() != $sale->user_id && !$sale->isSold)
                                    <form action="{{ route('sales.shop', $sale->id) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-primary w-100"
                                            onclick="return confirm('Are you sure you want to buy this product?')">
                                            <i class="fas fa-shopping-cart me-2"></i>Buy
                                        </button>
                                    </form>
                                @endif

                                @if(Auth::id() == $sale->user_id)
                                    <div class="btn-group flex-grow-1">
                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-edit me-2"></i>Update
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to eliminate this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('sales.show-modal', ['sale' => $sale])
            @endforeach
        </div>
    @endif
</div>
@endsection