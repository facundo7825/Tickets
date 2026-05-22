<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(10)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.confirmed'                => 'Las contraseñas no coinciden.',
        ]);

        Auth::user()->update([
            'password'            => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada correctamente.');
    }
}
