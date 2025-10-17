@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.restaurants') }}">المطاعم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories', $category->restaurant) }}">فئات
                                {{ $category->restaurant->name }}</a></li>
                        <li class="breadcrumb-item active">منتجات {{ $category->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">منتجات {{ $category->name }}</h2>
                        <p class="text-muted">إدارة منتجات الفئة</p>
                    </div>
                    <a href="{{ route('admin.products.create', $category) }}" class="btn btn-red-primary">
                        <i class="fas fa-plus me-2"></i>إضافة منتج
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>الصورة</th>
                                        <th>اسم المنتج</th>
                                        <th>السعر</th>
                                        <th>وقت التحضير</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="{{ $product->name }}"
                                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <i class="fas fa-pizza-slice fa-2x text-muted"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                @if ($product->description)
                                                    <br><small
                                                        class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td>{{ $product->preparation_time }} دقيقة</td>
                                            <td>
                                                <span
                                                    class="badge {{ $product->is_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $product->is_available ? 'متاح' : 'غير متاح' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-sm btn-outline-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($products->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-pizza-slice fa-3x text-muted mb-3"></i>
                                <p class="text-muted">لا توجد منتجات</p>
                                <a href="{{ route('admin.products.create', $category) }}" class="btn btn-red-primary">
                                    <i class="fas fa-plus me-2"></i>إضافة أول منتج
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
