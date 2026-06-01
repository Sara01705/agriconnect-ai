<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Product;
use App\Models\BuyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
class FarmerAuthController extends Controller
{
    // ================= LOGIN =================
    public function showLogin()
    {
        return view('farmer.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $farmer = Farmer::where('email', $request->email)->first();

        if ($farmer && Hash::check($request->password, $farmer->password)) {

            if ($farmer->is_blocked == 1) {
                return back()->with('error', 'Your account has been blocked by admin.');
            }

            Session::put('farmer_id', $farmer->id);
            Session::put('farmer_name', $farmer->name);

            return redirect()->route('farmer.dashboard');
        }

        return back()->with('error', 'Invalid login credentials');
    }

    // ================= REGISTER =================
    public function showRegister()
    {
        return view('farmer.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:farmers',
            'phone'    => 'required',
            'address'  => 'required',
            'district' => 'required',
            'state'    => 'required',
            'location' => 'nullable|string|max:255',
            'password' => 'required|min:6',
        ]);

        Farmer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'district' => $request->district,
            'state'    => $request->state,
            'location' => $request->location ?? null,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('farmer.login')
            ->with('success', 'Registration successful!');
    }

    // ================= DASHBOARD =================
    public function dashboard()
    {
        $farmerId = session('farmer_id');

        // Total Products
        $totalProducts = Product::where('farmer_id', $farmerId)->count();

        // Total Requests
        $totalRequests = BuyRequest::whereHas('product', function ($q) use ($farmerId) {
            $q->where('farmer_id', $farmerId);
        })->count();

        // Accepted
        $acceptedRequests = BuyRequest::whereHas('product', function ($q) use ($farmerId) {
            $q->where('farmer_id', $farmerId);
        })->where('status', 'Accepted')->count();

        // Rejected
        $rejectedRequests = BuyRequest::whereHas('product', function ($q) use ($farmerId) {
            $q->where('farmer_id', $farmerId);
        })->where('status', 'Rejected')->count();

        // Pending
        $pendingRequests = BuyRequest::whereHas('product', function ($q) use ($farmerId) {
            $q->where('farmer_id', $farmerId);
        })->where('status', 'Pending')->count();

        // Total Revenue
        $totalRevenue = BuyRequest::whereHas('product', function ($q) use ($farmerId) {
            $q->where('farmer_id', $farmerId);
        })
        ->where('status', 'Accepted')
        ->with('product')
        ->get()
        ->sum(function ($request) {
            return $request->quantity * $request->product->price;
        });

        // Top Selling Product
        $topProduct = BuyRequest::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('status', 'Accepted')
            ->whereHas('product', function($q) use ($farmerId){
                $q->where('farmer_id', $farmerId);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->first();

        // Recent Orders
        $recentOrders = BuyRequest::with(['product','user'])
            ->whereHas('product', function($q) use ($farmerId) {
                $q->where('farmer_id', $farmerId);
            })
            ->latest()
            ->take(5)
            ->get();

        // Stock Statistics
        $totalStockQuantity = Product::where('farmer_id', $farmerId)->sum('quantity');
        $lowStockProducts = Product::where('farmer_id', $farmerId)->where('quantity', '<=', 10)->get();
        $lowStockCount = $lowStockProducts->count();

        // Generate Revenue Chart Data (Last 6 Months)
        

$chartMonths = [];
$chartRevenues = [];

for ($i = 5; $i >= 0; $i--) {

    $date = Carbon::now()->startOfMonth()->subMonths($i);

    $monthStart = $date->copy()->startOfMonth();
    $monthEnd = $date->copy()->endOfMonth();

    $monthRevenue = BuyRequest::whereHas('product', function ($q) use ($farmerId) {
        $q->where('farmer_id', $farmerId);
    })
    ->where('status', 'Accepted')
    ->whereBetween('updated_at', [$monthStart, $monthEnd])
    ->with('product')
    ->get()
    ->sum(function ($request) {
        return $request->quantity * $request->product->price;
    });

    $chartMonths[] = $date->format('M');
    $chartRevenues[] = $monthRevenue;
}
        
        

        return view('farmer.dashboard', compact(
            'totalProducts',
            'totalRequests',
            'acceptedRequests',
            'rejectedRequests',
            'pendingRequests',
            'totalRevenue',
            'topProduct',
            'recentOrders',
            'totalStockQuantity',
            'lowStockProducts',
            'lowStockCount',
            'chartMonths',
            'chartRevenues'
        ));
    }

    // ================= ACCEPT REQUEST + AUTO WHATSAPP =================
public function acceptRequest($id)
{
    $request = BuyRequest::with(['user','product'])->findOrFail($id);

    $request->status = 'Accepted';
    $request->save();

    // Use USER now
    $phone = '+91' . $request->user->phone;

    $message = "AgriConnect:\nHello {$request->user->name},\n\nYour request for {$request->product->name} (Qty: {$request->quantity}) has been ACCEPTED.\n\nThank you for using AgriConnect.";

    Http::post("https://api.ultramsg.com/instance163639/messages/chat", [
        "token" => "59lx0hfcjgiegf9u",
        "to" => $phone,
        "body" => $message
    ]);

    return back()->with('success','Request accepted & WhatsApp sent!');
}
public function orders(Request $request)
{
    $query = Order::where('farmer_id', auth()->id());

    // Filter by status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $orders = $query->latest()->get();

    // Calculate total earnings (only accepted orders)
    $totalEarnings = Order::where('farmer_id', auth()->id())
                          ->where('status', 'accepted')
                          ->sum('total_price');

    return view('farmer.orders', compact('orders', 'totalEarnings'));
}

    // ================= LOGOUT =================
    public function logout()
    {
        Session::forget('farmer_id');
        Session::forget('farmer_name');

        return redirect()->route('farmer.login');
    }
}