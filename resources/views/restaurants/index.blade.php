@extends('layouts.app')

@section('content')


<head>



    <style>
.restaurant-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.restaurant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.restaurant-icon {
    color: var(--red-primary);
}

.section-title {
    position: relative;
    margin-bottom: 60px;
    font-weight: 700;
}

.section-title:after {
    content: '';
    position: absolute;
    bottom: -15px;
    right: 50%;
    transform: translateX(50%);
    width: 80px;
    height: 4px;
    background-color: var(--red-primary);
}
</style></head>
<body>


<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center section-title mb-5">مطاعمنا المتاحة</h2>
        </div>
    </div>

    <div class="row">
        @foreach($restaurants as $restaurant)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card restaurant-card h-100 animate-on-scroll">
                <div class="card-body text-center p-4">
                    @if($restaurant->logo)
                    <img src="{{ asset('storage/' . $restaurant->logo) }}"
                         alt="{{ $restaurant->name }}"
                         class="restaurant-logo mb-3"
                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
                    @else
                    <div class="restaurant-icon mb-3">
                        <i class="fas fa-utensils fa-3x text-red-primary"></i>
                    </div>
                    @endif

                    <h4 class="card-title">{{ $restaurant->name }}</h4>
                    <p class="text-muted mb-2">
                        <i class="fas fa-clock me-2"></i>{{ $restaurant->working_hours }}
                    </p>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>{{ Str::limit($restaurant->address, 50) }}
                    </p>

                    <div class="restaurant-actions">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-info-circle me-1"></i>التفاصيل
                        </a>
                        <a href="{{ route('orders.create', $restaurant) }}" class="btn btn-red-primary btn-sm">
                            <i class="fas fa-shopping-cart me-1"></i>اطلب الآن
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($restaurants->isEmpty())
    <div class="row">
        <div class="col-12 text-center">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>لا توجد مطاعم متاحة حالياً.
            </div>
        </div>
    </div>
    @endif
</div>
</body>

@endsection
