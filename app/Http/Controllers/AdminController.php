<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Farmer;
use App\Models\User;
use App\Models\BuyRequest;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        return view('admin.dashboard', [
            'farmers' => Farmer::count(),
            'users' => User::count(),
            'products' => Product::count(),
            'recentOrders' => BuyRequest::latest()->take(5)->get()
    ]);
        
        
    }

    // View all farmers
  public function farmers()
{
    $farmers = Farmer::with('products.buyRequests')->get();

    foreach ($farmers as $farmer) {

        $revenue = 0;

        foreach ($farmer->products as $product) {

            $revenue += $product->buyRequests()
                        ->where('status','accepted')
                        ->sum('total_price');

        }

        $farmer->revenue = $revenue;
    }

    $revenue = \App\Models\BuyRequest::where('status','accepted')
                ->sum('total_price');

    return view('admin.farmers', compact('farmers','revenue'));
}
    // View all products
    public function products()
    {
        $products = Product::with('farmer')->get();
        return view('admin.products', compact('products'));
    }

    // Delete product
    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Product deleted');
    }
    public function requests()
{
    $requests = \App\Models\BuyRequest::with(['product', 'product.farmer'])
        ->latest()
        ->get();

    return view('admin.requests', compact('requests'));
}
public function blockProduct($id)
{
    $product = Product::findOrFail($id);

    $product->status = 'Blocked';
    $product->save();

    return back()->with('success','Product blocked successfully');
}
public function unblockProduct($id)
{
    $product = Product::findOrFail($id);

    $product->status = 'Available';
    $product->save();
    

    return back()->with('success','Product unblocked successfully');
}


public function users(Request $request)
{
    $search = $request->search;

    $users = User::when($search, function ($query, $search) {
        $query->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('phone', 'like', "%$search%");
    })->get();

    return view('admin.users', compact('users', 'search'));
}

public function deleteUser($id)
{
    User::find($id)->delete();
    return back()->with('success', 'User Deleted');
}
// Show edit form
public function editUser($id)
{
    $user = User::findOrFail($id);
    return view('admin.edit_user', compact('user'));
}

// Update user
public function updateUser(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email'
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone
    ]);

    return redirect()->route('admin.users')->with('success', 'User Updated Successfully');
}
   public function verifyFarmer($id)
{
    $farmer = \App\Models\Farmer::findOrFail($id);
    $farmer->verified = 1;
    $farmer->save();

    return back()->with('success', 'Farmer Verified');
}


public function updateAvailability(Request $request, $id)
{
    $farmer = \App\Models\Farmer::findOrFail($id);
    $farmer->availability = $request->availability;
    $farmer->save();

    return back();
}
}
