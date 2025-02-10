<div class="modal fade" id="showModal-{{ $sale->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $sale->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel-{{ $sale->id }}">
                    <i class="fas fa-box me-2"></i>{{ $sale->product }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($sale->images->isNotEmpty() && $sale->images->count() == 1)
                            <img src="{{ asset('storage/' . $sale->images->first()->route) }}"
                                class="img-fluid rounded"
                                style="max-height: 400px; width: 100%; object-fit: contain;"
                                alt="{{ $sale->product }}">
                        @elseif($sale->images->count() > 1)
                            <div id="carousel-{{ $sale->id }}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner rounded">
                                    @foreach($sale->images as $image)
                                        <div class="carousel-item @if($loop->first) active @endif">
                                            <img src="{{ asset("storage/{$image->route}") }}"
                                                class="d-block w-100"
                                                style="height: 400px; object-fit: contain;"
                                                alt="{{ $sale->product }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $sale->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $sale->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        @else
                            <div class="text-center">
                                <img src="{{ asset('images/basica.png') }}"
                                    class="img-fluid rounded"
                                    style="max-height: 400px; object-fit: contain;"
                                    alt="Imagen por defecto">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="p-3">
                            <h4 class="text-primary mb-4">Detalles del Producto</h4>
                            
                            <div class="mb-3">
                                <label class="text-muted">Descripción:</label>
                                <p class="fw-bold">{{ $sale->description }}</p>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="text-muted">Categoría:</label>
                                    <p class="fw-bold">{{ $sale->category->name }}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted">Precio:</label>
                                    <p class="fw-bold text-success">{{ number_format($sale->price, 2, ',', '.') }}€</p>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="text-muted">Vendedor:</label>
                                    <p class="fw-bold">{{ $sale->user->name }}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted">Publicado:</label>
                                    <p class="fw-bold">{{ $sale->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            @if(Auth::id() != $sale->user_id && !$sale->isSold)
                                <form action="{{ route('sales.shop', $sale) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary btn-lg w-100"
                                        onclick="return confirm('¿Estás seguro de que quieres comprar este producto?')">
                                        <i class="fas fa-shopping-cart me-2"></i>Comprar Ahora
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>