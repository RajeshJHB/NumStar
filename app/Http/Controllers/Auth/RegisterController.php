<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        // Check if user already exists but is unverified
        $existingUser = User::where('email', $request->email)->first();
        
        if ($existingUser && ! $existingUser->hasVerifiedEmail()) {
            // Update existing unverified user instead of creating new one
            $existingUser->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
            $user = $existingUser;
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        // Role_2 will be assigned after email verification (see routes/web.php)
        // User will be logged in only after email verification

        event(new Registered($user));

        // Don't log user in - they must verify email first
        // Show message that verification email has been sent
        return redirect()->route('login')->with('status', 'Verification link has been sent to your email address. Please verify your email before logging in.');
    }
}
