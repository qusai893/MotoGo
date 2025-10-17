@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.restaurants') }}">المطاعم</a></li>
                    <li class="breadcrumb-item active">فئات {{ $restaurant->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">فئات {{ $restaurant->name }}</h2>
                    <p class="text-muted">إدارة فئات القائمة</p>
                </div>
                <a href="{{ route('admin.categories.create', $restaurant) }}" class="btn btn-red-primary">
                    <i class="fas fa-plus me-2"></i>إضافة فئة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>اسم الفئة</th>
                                    <th>الوصف</th>
                                    <th>ترتيب العرض</th>
                                    <th>الحالة</th>
                                    <th>عدد المنتجات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description ?: '-' }}</td>
                                    <td>{{ $category->sort_order }}</td>
                                    <td>
                                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $category->is_active ? 'مفعل' : 'غير مفعل' }}
                                        </span>
                                    </td>
                                    <td>{{ $category->products_count }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products', $category) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="إدارة المنتجات">
                                                <i class="fas fa-pizza-slice"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($categories->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد فئات</p>
                        <a href="{{ route('admin.categories.create', $restaurant) }}" class="btn btn-red-primary">
                            <i class="fas fa-plus me-2"></i>إضافة أول فئة
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
