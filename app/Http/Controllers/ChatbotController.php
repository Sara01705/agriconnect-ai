<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ask(Request $request)
    {
        $message = $request->message;
        $messageLower = strtolower($message);

        // Extract numerical values to evaluate potential price thresholds
        preg_match('/\d+/', $messageLower, $matches);
        $price = $matches[0] ?? null;

        // Path to local python executable
        $python = "C:\\Users\\sarat\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";
        $script = base_path("AI/chatbot_nlp.py");

        // Transmit request to Python NLP intent model
        $command = $python . " " . $script . " " . escapeshellarg($message);
        $output = shell_exec($command);
        $result = json_decode($output, true);

        $intent = $result['intent'] ?? "default";

        // Initialize default arrays for dynamic frontend UI elements
        $payload = [];
        $suggestions = ["🥬 Cheap Vegetables", "🍎 Available Fruits", "⭐ Top Recommendations", "❓ Help Guide"];

        /* ==========================================================================
           1. GREETING INTENT
           ========================================================================== */
        if ($intent == "greeting") {
            $reply = "Hello! 👋 Welcome to AgriConnect AI. How can I help you today?";
            $suggestions = ["🥬 Cheap Vegetables", "🍎 Available Fruits", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        /* ==========================================================================
           2. HELP INTENT
           ========================================================================== */
        elseif ($intent == "help") {
            $reply = "I am your smart agricultural assistant! Here are some of the things you can ask me:<br>
            • Find affordable items (e.g. <b>cheap vegetables</b>)<br>
            • List items within a budget (e.g. <b>vegetables under 50</b>)<br>
            • Ask for recommended items (e.g. <b>recommend products</b>)<br>
            • Get a guide on how to buy or sell products on the platform.";
            $suggestions = ["🥬 Cheap Vegetables", "🍎 Available Fruits", "⭐ Top Recommendations", "🛍️ How to Buy"];
        }

        /* ==========================================================================
           3. BUY PRODUCT INTENT
           ========================================================================== */
        elseif ($intent == "buy_product") {
            $reply = "To buy a product on AgriConnect:<br>
            1️⃣ Browse the marketplace to find fresh organic produce.<br>
            2️⃣ Click the <b>Buy Now</b> button on any item card.<br>
            3️⃣ Enter your desired quantity and submit your purchase request.<br>
            4️⃣ The farmer will receive your request and approve it. Track requests under <b>My Orders</b>!";
            $suggestions = ["🥬 Cheap Vegetables", "🍎 Available Fruits", "📦 Order Status", "❓ Help Guide"];
        }

        /* ==========================================================================
           4. CHEAP VEGETABLES INTENT
           ========================================================================== */
        elseif ($intent == "cheap_vegetables") {
            $payload = $this->getFormattedProducts('Vegetables', 3);
            
            if (empty($payload)) {
                $reply = "🥬 Sorry, we don't have any vegetables in stock right now. Please check back later!";
            } else {
                $reply = "🥬 Here are the most affordable fresh vegetables available on our marketplace:";
            }
            $suggestions = ["🍎 Cheap Fruits", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        /* ==========================================================================
           5. CHEAP FRUITS INTENT
           ========================================================================== */
        elseif ($intent == "cheap_fruits") {
            $payload = $this->getFormattedProducts('Fruits', 3);
            
            if (empty($payload)) {
                $reply = "🍎 Sorry, we don't have any fruits in stock right now. Please check back later!";
            } else {
                $reply = "🍎 Here are some budget-friendly organic fruits available right now:";
            }
            $suggestions = ["🥬 Cheap Vegetables", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        /* ==========================================================================
           6. SHOW VEGETABLES INTENT
           ========================================================================== */
        elseif ($intent == "show_vegetables") {
            $payload = $this->getFormattedProducts('Vegetables', 4, $price);
            
            if (empty($payload)) {
                $reply = $price 
                    ? "🥬 Sorry, we couldn't find any fresh vegetables under <b>₹{$price}</b> right now."
                    : "🥬 Sorry, no vegetables are currently listed.";
            } else {
                $reply = $price 
                    ? "🥬 Here are available fresh vegetables priced under <b>₹{$price}</b>:"
                    : "🥬 Here are some of the fresh vegetables currently in stock:";
            }
            $suggestions = ["🍎 Show Fruits", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        /* ==========================================================================
           7. SHOW FRUITS INTENT
           ========================================================================== */
        elseif ($intent == "show_fruits") {
            $payload = $this->getFormattedProducts('Fruits', 4, $price);
            
            if (empty($payload)) {
                $reply = $price 
                    ? "🍎 Sorry, we couldn't find any fresh fruits under <b>₹{$price}</b> right now."
                    : "🍎 Sorry, no fruits are currently listed.";
            } else {
                $reply = $price 
                    ? "🍎 Here are available fresh fruits priced under <b>₹{$price}</b>:"
                    : "🍎 Here are some of the delicious fruits currently in stock:";
            }
            $suggestions = ["🥬 Show Vegetables", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        /* ==========================================================================
           8. DAIRY PRODUCTS INTENT
           ========================================================================== */
        elseif ($intent == "dairy_products") {
            $payload = $this->getFormattedProducts('Dairy Products', 4, $price);
            
            if (empty($payload)) {
                $reply = $price 
                    ? "🥛 Sorry, we couldn't find dairy products under <b>₹{$price}</b> right now."
                    : "🥛 Sorry, no dairy products are currently in stock.";
            } else {
                $reply = $price 
                    ? "🥛 Here are fresh dairy products priced under <b>₹{$price}</b>:"
                    : "🥛 Here are high-quality farm dairy products available now:";
            }
            $suggestions = ["🥬 Cheap Vegetables", "🍎 Cheap Fruits", "❓ Help Guide"];
        }

        /* ==========================================================================
           9. RECOMMEND PRODUCTS INTENT
           ========================================================================== */
        elseif ($intent == "recommend_products") {
            $payload = $this->getFormattedProducts(null, 3);
            
            if (empty($payload)) {
                $reply = "⭐ Sorry, we don't have any marketplace items to recommend right now.";
            } else {
                $reply = "⭐ Based on popularity and quality, here are some handpicked recommendations for you:";
            }
            $suggestions = ["🥬 Cheap Vegetables", "🍎 Cheap Fruits", "❓ Help Guide"];
        }

        /* ==========================================================================
           10. ORDER HISTORY INTENT
           ========================================================================== */
        elseif ($intent == "order_history") {
            $reply = "You can view your complete purchase history and transaction records by visiting the <a href='/my-orders' class='auth-link'><b>My Orders</b></a> page.";
            $suggestions = ["📦 Order Status", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        /* ==========================================================================
           11. ORDER STATUS INTENT
           ========================================================================== */
        elseif ($intent == "order_status") {
            $reply = "You can track the active approval status of your buyer requests (Pending, Approved, or Rejected) on your <a href='/my-requests' class='auth-link'><b>My Requests</b></a> page.";
            $suggestions = ["🛍️ Order History", "🥬 Cheap Vegetables", "❓ Help Guide"];
        }

        /* ==========================================================================
           12. FARMER DASHBOARD INTENT
           ========================================================================== */
        elseif ($intent == "farmer_dashboard") {
            $reply = "If you are registered as a farmer, you can manage your inventory, sales, and buyer orders via your secure <a href='/farmer/dashboard' class='auth-link'><b>Farmer Dashboard</b></a>.";
            $suggestions = ["📦 Add Product", "📈 Check Earnings", "❓ Help Guide"];
        }

        /* ==========================================================================
           13. ADD PRODUCT INTENT
           ========================================================================== */
        elseif ($intent == "add_product") {
            $reply = "To list a new product for sale:<br>
            1️⃣ Navigate to your <b>Farmer Dashboard</b>.<br>
            2️⃣ Click the <b>Add New Product</b> button.<br>
            3️⃣ Fill in the name, category, quantity, unit, price, and upload an optional image.<br>
            4️⃣ Save to publish your listing to the marketplace instantly!";
            $suggestions = ["🌾 Dashboard", "📈 Check Earnings", "❓ Help Guide"];
        }

        /* ==========================================================================
           14. VIEW ORDERS INTENT
           ========================================================================== */
        elseif ($intent == "view_orders") {
            $reply = "Farmers can view incoming buyer requests and delivery orders in the <b>Orders</b> section of their dashboard.";
            $suggestions = ["🌾 Dashboard", "📈 Check Earnings", "❓ Help Guide"];
        }

        /* ==========================================================================
           15. ACCEPT ORDER INTENT
           ========================================================================== */
        elseif ($intent == "accept_order") {
            $reply = "To approve a buyer request:<br>
            1️⃣ Go to the <b>Farmer Orders</b> dashboard.<br>
            2️⃣ Find the pending request under incoming orders.<br>
            3️⃣ Review the details and click the green <b>Accept</b> button.";
            $suggestions = ["🌾 Dashboard", "❓ Help Guide"];
        }

        /* ==========================================================================
           16. REJECT ORDER INTENT
           ========================================================================== */
        elseif ($intent == "reject_order") {
            $reply = "To decline an incoming order request:<br>
            1️⃣ Navigate to the <b>Farmer Orders</b> dashboard.<br>
            2️⃣ Locate the request and click the red <b>Reject</b> button.";
            $suggestions = ["🌾 Dashboard", "❓ Help Guide"];
        }

        /* ==========================================================================
           17. EARNINGS INTENT
           ========================================================================== */
        elseif ($intent == "earnings") {
            $reply = "You can track your sales volume, total revenues, and individual earnings reports inside your <b>Farmer Dashboard</b>.";
            $suggestions = ["🌾 Dashboard", "📦 Add Product", "❓ Help Guide"];
        }

        /* ==========================================================================
           18. THANKS INTENT
           ========================================================================== */
        elseif ($intent == "thanks") {
            $reply = "You are very welcome! 😊 If you have any other questions, feel free to ask. Happy farming with AgriConnect!";
            $suggestions = ["🥬 Cheap Vegetables", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        /* ==========================================================================
           19. DEFAULT FALLBACK
           ========================================================================== */
        else {
            $reply = "I'm not completely sure I understood that request. 🤖 Could you please rephrase or try one of these popular topics:";
            $suggestions = ["🥬 Cheap Vegetables", "🍎 Available Fruits", "⭐ Top Recommendations", "❓ Help Guide"];
        }

        // Return structured JSON response matching AlpineJS expectations
        return response()->json([
            'reply' => $reply,
            'payload' => $payload,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Query products from the database and format them for the chatbot payload.
     *
     * @param  string|null  $category
     * @param  int  $limit
     * @param  float|null  $maxPrice
     * @return array
     */
    private function getFormattedProducts($category, $limit = 3, $maxPrice = null)
    {
        // Select available products that are not blocked by admin
        $query = Product::where('status', 'available')
                        ->where('admin_blocked', 0);

        if ($category) {
            $query->where('category', $category);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Order vegetables/fruits/dairy by lowest price, otherwise use random recommendations
        if ($category) {
            $query->orderBy('price', 'asc');
        } else {
            $query->inRandomOrder();
        }

        $products = $query->take($limit)->get();

        $payload = [];
        foreach ($products as $p) {
            $payload[] = [
                'id' => $p->id,
                'name' => ucfirst($p->product_name),
                'price' => $p->price,
                'unit' => $p->unit ?? 'Kg',
                'image' => $p->image ? asset('storage/' . $p->image) : 'https://via.placeholder.com/150'
            ];
        }

        return $payload;
    }
}