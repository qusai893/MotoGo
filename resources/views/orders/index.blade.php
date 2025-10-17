@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="section-title mb-4">طلباتي</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($orders->isEmpty())
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد طلبات سابقة</h5>
                    <a href="{{ route('restaurants.index') }}" class="btn btn-red-primary mt-3">
                        <i class="fas fa-utensils me-2"></i>تصفح المطاعم
                    </a>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>المطعم</th>
                                    <th>المبلغ الإجمالي</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->restaurant->name }}</td>
                                    <td>{{ $order->formatted_total }}</td>
                                    <td>
                                        <span class="badge
                                            @if($order->status == 'pending') bg-warning
                                            @elseif($order->status == 'confirmed') bg-info
                                            @elseif($order->status == 'preparing') bg-primary
                                            @elseif($order->status == 'ready') bg-success
                                            @elseif($order->status == 'delivered') bg-secondary
                                            @elseif($order->status == 'cancelled') bg-danger
                                            @endif">
                                            {{ $order->status_text }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
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
</style>
@endsection
