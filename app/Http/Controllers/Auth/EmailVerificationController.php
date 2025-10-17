<?php
// app/Http/Controllers/Auth/EmailVerificationController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Email doğrulama sayfasını göster
     */
    public function notice()
    {
        if (Auth::user()->email_verified_at) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Email doğrulama kodu gönder
     */
    public function send(Request $request)
    {
        if (Auth::user()->email_verified_at) {
            return redirect()->route('home');
        }

        // Burada email doğrulama kodu gönderme işlemini yapabilirsiniz
        // Şu anlık sadece başarılı mesajı döndürüyoruz

        return back()->with('status', 'Verification code sent to your email!');
    }

    /**
     * Email doğrulama kodu ile doğrula
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();

        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        // Burada kodu kontrol edip doğrulama yapılacak
        // Şu anlık direkt doğruluyoruz

        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('home')->with('status', 'Email verified successfully!');
    }
}
