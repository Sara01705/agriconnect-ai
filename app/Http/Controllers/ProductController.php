<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Farmer;
use Illuminate\Http\Request;
use App\Models\BuyRequest;
use App\Models\User;

class ProductController extends Controller
{
    // Show all products
public function index(Request $request)
{
    $recommendedProducts = collect(); 
    $nearbyProducts = collect();
    $recommendedIds = [];
    $reason = "";

    /*
    ====================================
    AI RECOMMENDATION
    ====================================
    */

    if(session()->has('user_id')){

        $userId = session('user_id');

        $hasOrders = BuyRequest::where('user_id',$userId)->exists();

        if($hasOrders){

           $python = env('PYTHON_PATH', 'python3');
            $scriptPath = base_path("AI/recommend.py");

            $command = "\"$pythonPath\" \"$scriptPath\" $userId";

            $output = shell_exec($command);

            $data = json_decode($output, true);

            $recommendedIds = $data['products'] ?? [];
            $reason = $data['reason'] ?? "AI recommendations";

            if(!empty($recommendedIds)){
                $recommendedProducts = Product::whereIn('id',$recommendedIds)
                    ->where('status','available')
                    ->get();
            }
        }

        /*
        ====================================
        NEARBY RECOMMENDATION (LOCATION-BASED)
        ====================================
        */
        $user = User::find($userId);
        if($user && ($user->district || $user->state)){
            $nearbyProducts = Product::where('status', 'available')
                ->whereHas('farmer', function($q) use ($user) {
                    if($user->district){
                        $q->where('district', $user->district);
                    } else {
                        $q->where('state', $user->state);
                    }
                })
                ->whereNotIn('id', $recommendedIds) // Exclude products already recommended by AI
                ->limit(5)
                ->get();
        }
    }

    /*
    ====================================
    BASE QUERY
    ====================================
    */

    $query = Product::query();

    /*
    ====================================
    FARMER FILTER
    ====================================
    */

    if(session()->has('farmer_id')){
        $query->where('farmer_id', session('farmer_id'));
    }

    /*
    ====================================
    HIDE RECOMMENDED PRODUCTS
    ====================================
    */

    if(!empty($recommendedIds)){
        $query->whereNotIn('id',$recommendedIds);
    }

    /*
    ====================================
    SEARCH
    ====================================
    */

    if($request->search){
        $query->where('product_name','like','%'.$request->search.'%');
    }

    /*
    ====================================
    CATEGORY FILTER
    ====================================
    */

    if($request->category){
        $query->where('category',$request->category);
    }

    /*
    ====================================
    SORTING
    ====================================
    */

    if($request->sort == "latest"){
        $query->latest();
    }
    elseif($request->sort == "price_low"){
        $query->orderBy('price','asc');
    }
    elseif($request->sort == "price_high"){
        $query->orderBy('price','desc');
    }

    /*
    ====================================
    FINAL PRODUCTS
    ====================================
    */

    $products = $query
        ->where('status','available')
        ->paginate(5)
        ->withQueryString();

    /*
    ====================================
    CATEGORY LIST
    ====================================
    */

    $categories = Product::select('category')
        ->distinct()
        ->pluck('category');

    /*
    ====================================
    RETURN VIEW
    ====================================
    */
    
   if($request->ajax() || $request->expectsJson()){
    return view('partials.products', compact('products'))->render();
}
    
    return view('products.index', compact(
        'products',
        'recommendedProducts',
        'nearbyProducts',
        'reason',
        'categories'
    ));
}

    


    // Store product
   public function store(Request $request)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
        'category' => 'required|string|max:100',
        'price' => 'required|numeric|min:1',
        'quantity' => 'required|numeric|min:1',
        'unit' => 'required|string|max:50',
        'status' => 'required',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->only([
        'product_name',
        'category',
        'description',
        'price',
        'quantity',
        'unit',
        'status'
    ]);

    // ✅ AUTO SET FARMER ID FROM SESSION
    $data['farmer_id'] = session('farmer_id');

    // Auto status if quantity = 0
    if ($request->quantity == 0) {
        $data['status'] = 'unavailable';
    }

    // Image upload
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    Product::create($data);

    return redirect()->route('products.index')
        ->with('success', 'Product added successfully');
}
public function create()
{
    if (!session()->has('farmer_id')) {
        return redirect()->route('farmer.login')
            ->with('error', 'Please login as farmer');
    }

    return view('products.create');
}
    public function show($id)
{
    $product = Product::with('farmer')->findOrFail($id);

    $alsoBought = collect();

    $orders = BuyRequest::where('product_id', $id)
                ->where('status', 'accepted')
                ->pluck('user_id');

    if ($orders->count() > 0) {

        $alsoBought = Product::whereIn('id', function($query) use ($orders) {
            $query->select('product_id')
                  ->from('buy_requests')
                  ->whereIn('user_id', $orders)
                  ->where('status', 'accepted');
        })
        ->where('id', '!=', $id)
        ->limit(4)
        ->get();
    }

    return view('products.show', compact('product', 'alsoBought'));
}
public function aiPriceSuggestion(Request $request)
{
    $name = $request->product_name;

    $products = Product::where('product_name', $name)->get();

    if($products->count() == 0){
        return response()->json([
            "suggested_price"=>0,
            "demand"=>"Low",
            "range_min"=>0,
            "range_max"=>0
        ]);
    }

    $avg = $products->avg('price');
    $min = $products->min('price');
    $max = $products->max('price');

    return response()->json([
        "suggested_price"=>round($avg),
        "demand"=>"Medium",
        "range_min"=>$min,
        "range_max"=>$max
    ]);
}


   public function edit($id)
{
    $product = Product::findOrFail($id);

    if(session('farmer_id') != $product->farmer_id){
        abort(403);
    }

    return view('products.edit',compact('product'));
}
    public function update(Request $request,$id)
{
    $product = Product::findOrFail($id);

    if(session('farmer_id') != $product->farmer_id){
        abort(403);
    }

    $product->update($request->all());

    return redirect()->route('products.index')
            ->with('success','Product updated successfully');
}


    // Delete product
  public function destroy($id)
{
    $product = Product::findOrFail($id);

    if(session('farmer_id') != $product->farmer_id){
        abort(403);
    }

    $product->delete();

    return redirect()->route('products.index')
            ->with('success','Product deleted successfully');
}
}

