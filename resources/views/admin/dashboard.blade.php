@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">لوحة التحكم</h2>
            <p class="text-muted">مرحباً بك في لوحة تحكم موتو جو</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card card-custom text-center">
                <div class="card-body">
                    <i class="fas fa-utensils fa-2x text-red-primary mb-3"></i>
                    <h3>{{ $restaurants->count() }}</h3>
                    <p class="text-muted">عدد المطاعم</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom text-center">
                <div class="card-body">
                    <i class="fas fa-list-alt fa-2x text-red-primary mb-3"></i>
                    <h3>{{ $restaurants->sum('categories_count') }}</h3>
                    <p class="text-muted">الفئات</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom text-center">
                <div class="card-body">
                    <i class="fas fa-pizza-slice fa-2x text-red-primary mb-3"></i>
                    <h3>{{ $restaurants->sum('products_count') }}</h3>
                    <p class="text-muted">المنتجات</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">المطاعم</h5>
                    <a href="{{ route('admin.restaurants.create') }}" class="btn btn-red-primary">
                        <i class="fas fa-plus me-2"></i>إضافة مطعم
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>اسم المطعم</th>
                                    <th>ساعات العمل</th>
                                    <th>الهاتف</th>
                                    <th>الفئات</th>
                                    <th>المنتجات</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurants as $restaurant)
                                <tr>
                                    <td>{{ $restaurant->name }}</td>
                                    <td>{{ $restaurant->working_hours }}</td>
                                    <td>{{ $restaurant->phone }}</td>
                                    <td>{{ $restaurant->categories_count }}</td>
                                    <td>{{ $restaurant->products_count }}</td>
                                    <td>
                                        <span class="badge {{ $restaurant->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $restaurant->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.categories', $restaurant) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                              <form action="{{ route('admin.restaurants.delete', $restaurant) }}"
                                                        method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                     <button class="btn btn-sm btn-outline-danger m-3" title="تعديل" type="submit"> <i
                                                            class="fas fa-trash"></i> </button>

                                                    </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
