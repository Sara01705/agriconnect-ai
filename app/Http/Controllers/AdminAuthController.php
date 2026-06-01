<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Farmer;
use App\Models\Product;
class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {

            Session::put('admin_id', $admin->id);
            Session::put('admin_name', $admin->name);

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid admin credentials');
    }

    public function logout()
    {
        Session::forget('admin_id');
        Session::forget('admin_name');

        return redirect()->route('admin.login');
    }
public function farmers()
{
    $farmers = Farmer::all();
    return view('admin.farmers', compact('farmers'));
}
public function blockProduct($id)
{
    $product = Product::findOrFail($id);
    $product->admin_blocked = true;
    $product->save();

    return back()->with('success', 'Product blocked successfully');
}

public function unblockProduct($id)
{
    $product = Product::findOrFail($id);
    $product->admin_blocked = false;
    $product->save();

    return back()->with('success', 'Product unblocked successfully');
}
}
