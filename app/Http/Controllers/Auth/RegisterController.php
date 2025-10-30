<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerificationCode;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        Log::info('Registration validator called', $data);

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'terms.required' => 'يجب الموافقة على الشروط والأحكام',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
            'verification_code.required' => 'يرجى إدخال رمز التحقق',
            'verification_code.size' => 'رمز التحقق يجب أن يكون 6 أرقام',
        ]);
    }

    public function register(Request $request)
    {
        Log::info('Registration request started', $request->all());

        // Önce email doğrulamasını kontrol et
        $verification = EmailVerificationCode::where('email', $request->email)
            ->where('code', $request->verification_code)
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            Log::error('Email verification failed for registration', [
                'email' => $request->email,
                'code' => $request->verification_code
            ]);
            return redirect()->back()
                ->withErrors(['verification_code' => 'يرجى التحقق من البريد الإلكتروني أولاً'])
                ->withInput();
        }

        // Validasyonu yap
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            Log::error('Registration validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kullanıcıyı oluştur
        try {
            $user = $this->create($request->all());

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Kullanılan kodu temizle
            EmailVerificationCode::where('email', $request->email)->delete();

            // Kullanıcıyı login et ve event fırlat
            $this->guard()->login($user);
            event(new Registered($user));

            Log::info('User registered and logged in successfully');

            return redirect($this->redirectPath());
        } catch (\Exception $e) {
            Log::error('User creation failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'فشل في إنشاء الحساب. يرجى المحاولة مرة أخرى.'])
                ->withInput();
        }
    }

    protected function create(array $data)
    {
        Log::info('Creating user with data', $data);

        try {
            // Önce email doğrulama kodunu kontrol et
            $verification = EmailVerificationCode::where('email', $data['email'])
                ->where('verified', true)
                ->where('expires_at', '>', now())
                ->first();

            // Eğer doğrulama kodu varsa ve doğrulanmışsa, email_verified_at doldur
            $emailVerifiedAt = $verification ? now() : null;

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => $emailVerifiedAt,
            ]);

            Log::info('User created in database', [
                'user_id' => $user->id,
                'email' => $user->email,
                'email_verified' => !is_null($emailVerifiedAt)
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Database error during user creation', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
