<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update([
            'email' => $request->email
        ]);

        return back()->with('success', 'Email actualizado correctamente');
    }

    public function changeUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . Auth::id(),
        ]);

        Auth::user()->update([
            'username' => $request->username
        ]);

        return back()->with('success', 'Nombre de usuario actualizado correctamente');
    }
}