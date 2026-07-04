<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        if (!config('services.google.client_id')) {
            return redirect()->route('login')->with('error', 'Google Login belum dikonfigurasi.');
        }
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                Auth::login($user);
            } else {
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'petugas', // Default role tugas besar
                    'is_active' => true,
                ]);

                Auth::login($newUser);
            }

            return redirect()->route('dashboard');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}