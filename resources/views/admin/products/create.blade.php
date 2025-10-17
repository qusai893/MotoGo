@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.restaurants') }}">المطاعم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories', $category->restaurant) }}">فئات {{ $category->restaurant->name }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products', $category) }}">منتجات {{ $category->name }}</a></li>
                    <li class="breadcrumb-item active">إضافة منتج</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h4 class="card-title">إضافة منتج جديد لـ {{ $category->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store', $category) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">السعر <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preparation_time" class="form-label">وقت التحضير (دقيقة) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('preparation_time') is-invalid @enderror"
                                           id="preparation_time" name="preparation_time" value="{{ old('preparation_time', 15) }}" min="1" required>
                                    @error('preparation_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">صورة المنتج</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">الصور المسموح بها: JPEG, PNG, JPG, GIF. الحد الأقصى للحجم: 2MB</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">متاح للطلب</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.products', $category) }}" class="btn btn-secondary me-2">إلغاء</a>
                            <button type="submit" class="btn btn-red-primary">حفظ المنتج</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
