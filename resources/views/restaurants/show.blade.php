@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if($restaurant->logo)
                            <img src="{{ asset('storage/' . $restaurant->logo) }}"
                                 alt="{{ $restaurant->name }}"
                                 class="img-fluid rounded"
                                 style="max-height: 120px;">
                            @else
                            <i class="fas fa-utensils fa-4x text-red-primary"></i>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h1 class="h3">{{ $restaurant->name }}</h1>
                            <p class="text-muted mb-2">
                                <i class="fas fa-clock me-2"></i>ساعات العمل: {{ $restaurant->working_hours }}
                            </p>
                            <p class="text-muted mb-2">
                                <i class="fas fa-phone me-2"></i>{{ $restaurant->phone }}
                            </p>
                            <p class="text-muted mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $restaurant->address }}
                            </p>
                            <a href="{{ route('orders.create', $restaurant) }}" class="btn btn-red-primary btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i>اطلب من هذا المطعم
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-red-primary text-white">
                    <h4 class="mb-0">قائمة الطعام</h4>
                </div>
                <div class="card-body">
                    @foreach($categories as $category)
                    <div class="category-section mb-5">
                        <h5 class="category-title mb-3">{{ $category->name }}</h5>
                        @if($category->description)
                        <p class="text-muted mb-3">{{ $category->description }}</p>
                        @endif

                        <div class="row">
                            @foreach($category->activeProducts as $product)
                            <div class="col-md-6 mb-3">
                                <div class="card product-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $product->name }}</h6>
                                            <span class="text-red-primary fw-bold">{{ $product->formatted_price }}</span>
                                        </div>
                                        @if($product->description)
                                        <p class="card-text text-muted small">{{ $product->description }}</p>
                                        @endif
                                        <div class="product-meta">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>تحضير: {{ $product->preparation_time }} دقيقة
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    @if($categories->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد أصناف متاحة حالياً.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.category-title {
    color: var(--dark-gray);
    border-bottom: 2px solid var(--red-primary);
    padding-bottom: 0.5rem;
}

.product-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.product-card:hover {
    border-color: var(--red-primary);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
</style>
@endsection
