<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BuyRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderAcceptedMail;
use App\Mail\OrderRejectedMail;
use App\Models\User;

class BuyRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Contact Form
    |--------------------------------------------------------------------------
    */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('buy_requests.create', compact('product'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Buy Request
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $productId)
    {
   $request->validate([
    'quantity' => 'required|integer|min:1'
]);
        $product = Product::findOrFail($productId);

        $total = $product->price * $request->quantity;
$user = User::find(session('user_id'));
$user->phone = $request->phone;
$user->save();
       
        BuyRequest::create([
    'product_id' => $product->id,
    'product_name' => $product->product_name, 
    'user_id' => session('user_id'),
    'quantity' => $request->quantity,
    'total_price' => $total,
    'status' => 'pending',
    

]);

        return redirect()->route('user.requests')
    ->with('success', 'Buy Request Sent Successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Buyer: View My Requests
    |--------------------------------------------------------------------------
    */
    public function myRequests()
    {
        $requests = BuyRequest::with('product')
            ->where('user_id', session('user_id'))
            ->latest()
            ->get();

        return view('buy_requests.my_requests', compact('requests'));
    }

    /*
    |--------------------------------------------------------------------------
    | Farmer: View Incoming Requests
    |--------------------------------------------------------------------------
    */
  public function farmerRequests()
{
    $farmerId = session('farmer_id');

    $requests = BuyRequest::with(['user', 'product']) // 👈 ADD HERE
        ->whereHas('product', function ($q) use ($farmerId) {
            $q->where('farmer_id', $farmerId);
        })
        ->latest()
        ->get();

    return view('farmer.requests', compact('requests'));
}
public function farmerOrders(Request $request)
{
    $farmerId = session('farmer_id');

    // Base query (farmer products only)
    $query = BuyRequest::whereHas('product', function ($q) use ($farmerId) {
        $q->where('farmer_id', $farmerId);
    })->with(['user', 'product']);

    // 🔎 Search by user name
    if ($request->filled('search_user')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search_user . '%');
        });
    }

    // 🔎 Search by product name
    if ($request->filled('search_product')) {
        $query->whereHas('product', function ($q) use ($request) {
            $q->where('product_name', 'like', '%' . $request->search_product . '%');
        });
    }

    // 📅 Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('created_at', [
            $request->from_date . ' 00:00:00',
            $request->to_date . ' 23:59:59'
        ]);
    }

    // 📌 Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Clone query BEFORE pagination
    $earningsQuery = clone $query;

    // 📄 Paginated results
    $orders = $query->latest()->paginate(10)->withQueryString();

    // 💰 Calculate filtered earnings (only accepted)
    $totalEarnings = $earningsQuery
                        ->where('status', 'accepted')
                        ->sum('total_price');

    return view('farmer.orders', compact('orders', 'totalEarnings'));
}
    /*
    |--------------------------------------------------------------------------
    */
    

public function accept($id)
{
    $req = BuyRequest::with('product','user')->findOrFail($id);

    if ($req->status == 'accepted') {
        return back();
    }

    // Check stock
    if ($req->quantity > $req->product->quantity) {
        return back()->with('error', 'Insufficient stock available.');
    }

    // Deduct stock
    $req->product->quantity -= $req->quantity;
    
    // Update status based on remaining stock
    if ($req->product->quantity <= 0) {
        $req->product->status = 'Out of Stock'; // or 'Unavailable' based on system
    }
    $req->product->save();

    $req->status = 'accepted';
    $req->save();

    // EMAIL
    try{
        Mail::to($req->user->email)->send(new OrderAcceptedMail($req));
    }catch(\Exception $e){}

    // PHONE FORMAT
    $phone = preg_replace('/[^0-9]/','',$req->user->phone);

    if(!str_starts_with($phone,'91')){
        $phone = '91'.$phone;
    }

    $message = urlencode(
"AgriConnect Marketplace

Hello {$req->user->name},

Your purchase request has been ACCEPTED.

Product: {$req->product->product_name}
Quantity: {$req->quantity}
Total Price: Rs {$req->total_price}

Please contact the farmer for delivery.

Thank you."
    );

    $whatsappLink = "https://wa.me/".$phone."?text=".$message;
    return back()
    ->with('success', 'Request Accepted!')
    ->with('whatsapp_link', $whatsappLink);
}
public function reject($id)

{
    $req = BuyRequest::with('product','user')->findOrFail($id);

    // Prevent double reject
    if ($req->status === 'rejected') {
        return back();
    }

    // Update status
    $req->status = 'rejected';
    $req->save();

    /*
    |--------------------------------------------------------------------------
    | 1️⃣ SEND EMAIL (WITH EMOJIS)
    |--------------------------------------------------------------------------
    */

    Mail::to($req->user->email)->send(new OrderRejectedMail($req));

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ SEND WHATSAPP (NO EMOJIS)
    |--------------------------------------------------------------------------
    */

    // Format phone number
    $phone = preg_replace('/[^0-9]/', '', $req->user->phone);

    if (!str_starts_with($phone, '91')) {
        $phone = '91' . $phone;
    }

    // Plain text WhatsApp message
    $message = urlencode(
"AgriConnect Marketplace

Hello {$req->user->name},

We regret to inform you that your purchase request has been REJECTED.

Product: {$req->product->product_name}
Quantity: {$req->quantity}

The product may be out of stock.

Please explore other products on AgriConnect.

Thank you."
    );

    $link = "https://wa.me/{$phone}?text={$message}";

    return back()
        ->with('success', 'Request Rejected!')
        ->with('whatsapp_link', $link);
}
public function cancel($id)
{
    $request = \App\Models\BuyRequest::find($id);

    $request->status = 'cancelled';
    $request->save();

    return redirect()->back()->with('success', 'Request cancelled');
}
}
