@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">طلباتي</a></li>
                    <li class="breadcrumb-item active">تفاصيل الطلب #{{ $order->id }}</li>
                </ol>
            </nav>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الطلب #{{ $order->id }}</h4>
                    <span class="badge
                        @if($order->status == 'pending') bg-warning
                        @elseif($order->status == 'confirmed') bg-info
                        @elseif($order->status == 'preparing') bg-primary
                        @elseif($order->status == 'ready') bg-success
                        @elseif($order->status == 'delivered') bg-secondary
                        @elseif($order->status == 'cancelled') bg-danger
                        @endif" style="font-size: 1rem;">
                        {{ $order->status_text }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>معلومات المطعم</h6>
                            <p class="mb-2"><strong>اسم المطعم:</strong> {{ $order->restaurant->name }}</p>
                            <p class="mb-2"><strong>الهاتف:</strong> {{ $order->restaurant->phone }}</p>
                            <p class="mb-0"><strong>العنوان:</strong> {{ $order->restaurant->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>معلومات التوصيل</h6>
                            <p class="mb-2"><strong>عنوان التوصيل:</strong> {{ $order->delivery_address }}</p>
                            <p class="mb-2"><strong>هاتف العميل:</strong> {{ $order->phone }}</p>
                            <p class="mb-0"><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>

                    @if($order->customer_notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>ملاحظات العميل</h6>
                            <p class="text-muted">{{ $order->customer_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">الأصناف المطلوبة</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>الصنف</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>الإجمالي</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->formatted_unit_price }}</td>
                                    <td>{{ $item->formatted_total_price }}</td>
                                    <td>{{ $item->notes ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>المبلغ الإجمالي:</strong></td>
                                    <td colspan="2" class="text-start">
                                        <strong class="text-red-primary">{{ $order->formatted_total }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
                <a href="{{ route('restaurants.show', $order->restaurant) }}" class="btn btn-red-primary">
                    <i class="fas fa-utensils me-2"></i>طلب جديد من هذا المطعم
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
