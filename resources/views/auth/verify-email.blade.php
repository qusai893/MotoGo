{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>تنبيه!</strong> يرجى التحقق من بريدك الإلكتروني قبل المتابعة.
                        </div>

                        <p>
                            قبل المتابعة، يرجى التحقق من بريدك الإلكتروني للحصول على رمز التحقق.
                            إذا لم تستلم البريد الإلكتروني،
                        </p>

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> إرسال رمز التحقق مرة أخرى
                            </button>
                        </form>

                        <hr>

                        <h5>أدخل رمز التحقق:</h5>
                        <form method="POST" action="{{ route('verification.verify') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">رمز التحقق (6 أرقام)</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="code" name="code" maxlength="6" required>
                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> تحقق
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
