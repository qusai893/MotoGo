@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">المطاعم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $restaurant) }}">{{ $restaurant->name }}</a></li>
                    <li class="breadcrumb-item active">طلب جديد</li>
                </ol>
            </nav>

            <h2 class="mb-4">طلب من {{ $restaurant->name }}</h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="orderForm" action="{{ route('orders.store', $restaurant) }}" method="POST">
                @csrf

                <!-- Menu Items -->
                <div class="card mb-4">
                    <div class="card-header bg-red-primary text-white">
                        <h5 class="mb-0">اختر الأصناف</h5>
                    </div>
                    <div class="card-body">
                        @foreach($categories as $category)
                        <div class="category-section mb-4">
                            <h6 class="category-title">{{ $category->name }}</h6>
                            @if($category->description)
                            <p class="text-muted small mb-3">{{ $category->description }}</p>
                            @endif

                            <div class="row">
                                @foreach($category->activeProducts as $product)
                                <div class="col-12 mb-3">
                                    <div class="card product-item-card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                                    @if($product->description)
                                                    <p class="text-muted small mb-2">{{ $product->description }}</p>
                                                    @endif
                                                    <div class="product-meta">
                                                        <small class="text-muted me-3">
                                                            <i class="fas fa-clock me-1"></i>{{ $product->preparation_time }} دقيقة
                                                        </small>
                                                        <small class="text-red-primary fw-bold">{{ $product->formatted_price }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="quantity-controls me-3">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm decrease-btn"
                                                                    data-product="{{ $product->id }}">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" class="form-control form-control-sm quantity-input text-center mx-2"
                                                                   data-product="{{ $product->id }}"
                                                                   data-price="{{ $product->price }}"
                                                                   value="0" min="0" max="10" style="width: 60px;" readonly>
                                                            <button type="button" class="btn btn-outline-secondary btn-sm increase-btn"
                                                                    data-product="{{ $product->id }}">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <div class="product-total" data-product="{{ $product->id }}" style="min-width: 80px; text-align: center;">
                                                            0.00 ₺
                                                        </div>
                                                    </div>
                                                </div>
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
                            <p class="text-muted">لا توجد أصناف متاحة للطلب حالياً.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">معلومات التوصيل</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="delivery_address" class="form-label">عنوان التوصيل *</label>
                                    <textarea class="form-control" id="delivery_address" name="delivery_address"
                                              rows="3" required>{{ old('delivery_address', Auth::user()->address ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                           value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="customer_notes" class="form-label">ملاحظات إضافية</label>
                                    <textarea class="form-control" id="customer_notes" name="customer_notes"
                                              rows="3">{{ old('customer_notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">إجمالي الطلب</h5>
                            <h5 class="mb-0 text-red-primary" id="totalAmount">0.00 ₺</h5>
                        </div>
                        <button type="submit" class="btn btn-red-primary btn-lg w-100" id="submitBtn" disabled>
                            <i class="fas fa-paper-plane me-2"></i>تأكيد الطلب
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.category-title {
    color: var(--dark-gray);
    border-bottom: 2px solid var(--red-primary);
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.product-item-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.product-item-card:hover {
    border-color: var(--red-primary);
}

.quantity-controls {
    display: flex;
    align-items: center;
}

.quantity-controls .btn {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-input {
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderForm = document.getElementById('orderForm');
    const totalAmountElement = document.getElementById('totalAmount');
    const submitBtn = document.getElementById('submitBtn');
    let totalAmount = 0;
    let selectedItems = [];

    // Increase quantity
    document.querySelectorAll('.increase-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product');
            const input = document.querySelector(`.quantity-input[data-product="${productId}"]`);
            const currentValue = parseInt(input.value);
            if (currentValue < 10) {
                input.value = currentValue + 1;
                updateOrder();
            }
        });
    });

    // Decrease quantity
    document.querySelectorAll('.decrease-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product');
            const input = document.querySelector(`.quantity-input[data-product="${productId}"]`);
            const currentValue = parseInt(input.value);
            if (currentValue > 0) {
                input.value = currentValue - 1;
                updateOrder();
            }
        });
    });

    function updateOrder() {
        totalAmount = 0;
        selectedItems = [];

        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = parseInt(input.value);
            const productId = input.getAttribute('data-product');
            const price = parseFloat(input.getAttribute('data-price'));

            if (quantity > 0) {
                const itemTotal = quantity * price;
                totalAmount += itemTotal;

                // Update product total display
                const productTotalElement = document.querySelector(`.product-total[data-product="${productId}"]`);
                if (productTotalElement) {
                    productTotalElement.textContent = itemTotal.toFixed(2) + ' ₺';
                }

                selectedItems.push({
                    product_id: productId,
                    quantity: quantity,
                    notes: ''
                });
            } else {
                // Reset product total display
                const productTotalElement = document.querySelector(`.product-total[data-product="${productId}"]`);
                if (productTotalElement) {
                    productTotalElement.textContent = '0.00 ₺';
                }
            }
        });

        // Update total amount
        totalAmountElement.textContent = totalAmount.toFixed(2) + ' ₺';

        // Enable/disable submit button
        submitBtn.disabled = selectedItems.length === 0;

        // Update hidden inputs
        updateHiddenInputs();
    }

    function updateHiddenInputs() {
        // Remove existing item inputs
        document.querySelectorAll('input[name^="items"]').forEach(input => {
            input.remove();
        });

        // Add new item inputs
        selectedItems.forEach((item, index) => {
            Object.keys(item).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${index}][${key}]`;
                input.value = item[key];
                orderForm.appendChild(input);
            });
        });
    }

    // Initial update
    updateOrder();
});
</script>
@endsection
