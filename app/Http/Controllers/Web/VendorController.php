<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function showRegister()
    {
        return view('auth.vendor-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'vendor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_vendor' => true,
        ]);

        Vendor::create([
            'user_id' => $user->id,
            'name' => $validated['vendor_name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'location' => $validated['location'],
            'description' => $validated['description'] ?? '',
            'verify_status' => 'pending',
        ]);

        Auth::login($user);

        return redirect('/events')->with('success', 'Pendaftaran vendor berhasil! Menunggu verifikasi admin.');
    }
}
