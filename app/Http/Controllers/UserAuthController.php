<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserAuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('user.login');
    }

    // Handle login
   public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {

        // Clear farmer session
        session()->forget(['farmer_id','farmer_name']);

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_phone' => $user->phone
        ]);

        session()->regenerate();

        return redirect()->route('products.index');
    }

    return back()->with('error', 'Invalid credentials');
}

    // Show registration page
    public function showRegister()
    {
        return view('user.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|digits:10|unique:users',
            'address' => 'required|max:100',
            'district' => 'required',
            'state' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'district' => $request->district,
            'state' => $request->state,
        ]);

        return redirect()->route('user.login')
            ->with('success', 'Registration successful. Please login.');
    }

    // Logout
   public function logout()
{
    session()->flush();
    return redirect()->route('user.login');
}
}
