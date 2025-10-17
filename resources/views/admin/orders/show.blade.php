@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">الطلبات</a></li>
                    <li class="breadcrumb-item active">تفاصيل الطلب #{{ $order->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-custom mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الطلب #{{ $order->id }}</h4>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select me-2" onchange="this.form.submit()">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>تم التأكيد</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>قيد التحضير</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>جاهز للتسليم</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>معلومات العميل</h6>
                            <p class="mb-2"><strong>الاسم:</strong> {{ $order->user->name }}</p>
                            <p class="mb-2"><strong>البريد الإلكتروني:</strong> {{ $order->user->email }}</p>
                            <p class="mb-2"><strong>هاتف العميل:</strong> {{ $order->phone }}</p>
                            <p class="mb-0"><strong>عنوان التوصيل:</strong> {{ $order->delivery_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>معلومات المطعم</h6>
                            <p class="mb-2"><strong>اسم المطعم:</strong> {{ $order->restaurant->name }}</p>
                            <p class="mb-2"><strong>هاتف المطعم:</strong> {{ $order->restaurant->phone }}</p>
                            <p class="mb-0"><strong>عنوان المطعم:</strong> {{ $order->restaurant->address }}</p>
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

            <div class="card card-custom">
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
        </div>

        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-header">
                    <h5 class="mb-0">معلومات الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>رقم الطلب:</strong> #{{ $order->id }}
                    </div>
                    <div class="mb-3">
                        <strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y/m/d H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>آخر تحديث:</strong> {{ $order->updated_at->format('Y/m/d H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>الحالة الحالية:</strong>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
