@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.restaurants') }}">المطاعم</a></li>
                    <li class="breadcrumb-item active">تعديل مطعم</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h4 class="card-title">تعديل مطعم: {{ $restaurant->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.restaurants.update', $restaurant) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المطعم <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $restaurant->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $restaurant->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="opening_time" class="form-label">ساعة الفتح <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('opening_time') is-invalid @enderror"
                                           id="opening_time" name="opening_time"
                                           value="{{ old('opening_time', $restaurant->opening_time->format('H:i')) }}" required>
                                    @error('opening_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="closing_time" class="form-label">ساعة الإغلاق <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('closing_time') is-invalid @enderror"
                                           id="closing_time" name="closing_time"
                                           value="{{ old('closing_time', $restaurant->closing_time->format('H:i')) }}" required>
                                    @error('closing_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" required>{{ old('address', $restaurant->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">شعار المطعم</label>
                            @if($restaurant->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}"
                                     style="max-height: 100px; border-radius: 5px;">
                                <div class="form-text">الشعار الحالي</div>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                   id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">اتركه فارغاً إذا كنت لا تريد تغيير الشعار</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $restaurant->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">مفعل</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.restaurants') }}" class="btn btn-secondary me-2">إلغاء</a>
                            <button type="submit" class="btn btn-red-primary">تحديث المطعم</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
