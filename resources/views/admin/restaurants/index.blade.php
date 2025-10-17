@extends('layouts.admin') @section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">إدارة المطاعم</h2>
                        <p class="text-muted">عرض وإدارة جميع المطاعم</p>
                    </div> <a href="{{ route('admin.restaurants.create') }}" class="btn btn-red-primary"> <i
                            class="fas fa-plus me-2"></i>إضافة مطعم </a>
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
                                        <th>الشعار</th>
                                        <th>اسم المطعم</th>
                                        <th>ساعات العمل</th>
                                        <th>الهاتف</th>
                                        <th>العنوان</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($restaurants as $restaurant)
                                        <tr>
                                            <td>
                                                @if ($restaurant->logo)
                                                    <img src="{{ asset('storage/' . $restaurant->logo) }}"
                                                        alt="{{ $restaurant->name }}"
                                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <i class="fas fa-utensils fa-2x text-muted"></i>
                                                @endif
                                            </td>
                                            <td>{{ $restaurant->name }}</td>
                                            <td>{{ $restaurant->working_hours }}</td>
                                            <td>{{ $restaurant->phone }}</td>
                                            <td>{{ Str::limit($restaurant->address, 30) }}</td>
                                            <td> <span
                                                    class="badge {{ $restaurant->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $restaurant->is_active ? 'مفعل' : 'غير مفعل' }} </span> </td>
                                            <td>
                                                <div class="btn-group"> <a
                                                        href="{{ route('admin.categories', $restaurant) }}"
                                                        class="btn btn-sm btn-outline-primary" title="إدارة الفئات"> <i
                                                            class="fas fa-list"></i> </a> <a
                                                        href="{{ route('admin.restaurants.edit', $restaurant) }}"
                                                        class="btn btn-sm btn-outline-warning" title="تعديل"> <i
                                                            class="fas fa-edit"></i> </a>




                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($restaurants->isEmpty())
                            <div class="text-center py-5"> <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                                <p class="text-muted">لا توجد مطاعم</p> <a href="{{ route('admin.restaurants.create') }}"
                                    class="btn btn-red-primary"> <i class="fas fa-plus me-2"></i>إضافة أول مطعم </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
