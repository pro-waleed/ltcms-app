<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return back()->with('status', $this->messageForStatus($status));
    }

    public function resetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', $this->messageForStatus($status));
        }

        return back()->withErrors([
            'email' => [$this->messageForStatus($status)],
        ]);
    }

    private function messageForStatus(string $status): string
    {
        return match ($status) {
            Password::RESET_LINK_SENT => 'تم إرسال رابط استعادة كلمة المرور إلى بريدك الإلكتروني.',
            Password::PASSWORD_RESET => 'تم تحديث كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.',
            Password::INVALID_TOKEN => 'رابط الاستعادة غير صالح أو منتهي الصلاحية.',
            Password::INVALID_USER => 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني.',
            Password::RESET_THROTTLED => 'تم إرسال طلب استعادة مؤخرًا. حاول مرة أخرى بعد قليل.',
            default => 'تعذر إكمال طلب استعادة كلمة المرور حاليًا.',
        };
    }
}
