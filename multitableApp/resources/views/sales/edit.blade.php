@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="card-title mb-0 text-center">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="list-unstyled mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control @error('product') is-invalid @enderror" 
                                           id="product" 
                                           name="product" 
                                           value="{{ old('product', $sale->product) }}" 
                                           placeholder="Product name"
                                           required>
                                    <label for="product">Product Name</label>
                                    @error('product')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           step="0.05" 
                                           min="0" 
                                           value="{{ old('price', $sale->price) }}"
                                           placeholder="Price"
                                           required>
                                    <label for="price">Price (€)</label>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id" 
                                            required>
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $sale->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="category_id">Category</label>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="images" class="form-label">
                                        <i class="fas fa-images me-2"></i>Product Images
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('images') is-invalid @enderror" 
                                           id="images" 
                                           name="images[]" 
                                           multiple 
                                           accept="image/*">
                                           <div class="mb-3">
                                    <div class="form-text">You can select up to {{ $maxFiles }} images. Supported formats: JPEG, PNG, GIF, SVG</div>
                                    
                                    <!-- para mostrar imágenes existentes -->
                                    @if($sale->images->isNotEmpty())
                                        <div class="row mt-3">
                                            @foreach($sale->images->sortByDesc('is_main') as $image)
                                                <div class="col-md-4 mb-3" data-image-id="{{ $image->id }}">
                                                    <div class="card">
                                                        <img src="{{ asset('storage/' . $image->route) }}" 
                                                            class="card-img-top" 
                                                            alt="Product image"
                                                            style="height: 200px; object-fit: contain;">
                                                        <div class="card-body">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" 
                                                                    type="radio" 
                                                                    name="main_image" 
                                                                    value="{{ $image->id }}"
                                                                    {{ $image->is_main ? 'checked' : '' }}>
                                                                <label class="form-check-label">
                                                                    Set as main image
                                                                </label>
                                                            </div>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm w-100"
                                                                    onclick="deleteImage({{ $image->id }})">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    @error('images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              style="height: 150px"
                                              placeholder="Description"
                                              required>{{ old('description', $sale->description) }}</textarea>
                                    <label for="description">Product Description</label>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select @error('isSold') is-invalid @enderror" 
                                            id="isSold" 
                                            name="isSold" 
                                            required>
                                        <option value="0" {{ old('isSold', $sale->isSold) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('isSold', $sale->isSold) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    <label for="isSold">Is Sold</label>
                                    @error('isSold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-save me-2"></i>Update Product
                            </button>
                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/script.js') }}"></script>
<script>
    document.getElementById('images').addEventListener('change', function() {
        handleImageSelection(this, {{ $maxFiles }});
    });
</script>
<!-- Se añade un script para controlar la eliminación de imégenes porque al hacerlo con formularios anidados daba error y borraba el producto -->
<script src="{{ asset('js/deleteImg.js') }}"></script>
@endsection