<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\table_teams;    
use App\Models\produks; 
use App\Models\Driver;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class landingController extends Controller
{
    public function index()
    {
        $topIds = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->whereIn('orders.status', ['approved','processing','shipped','delivered'])
        ->select('order_items.product_id', DB::raw('SUM(order_items.qty) as sold'))
        ->groupBy('order_items.product_id')
        ->orderByDesc('sold')
        ->limit(4)
        ->pluck('product_id');

        $teams = table_teams::all();
        $bestSellers = $topIds->isEmpty()
        ? produks::latest()->take(4)->get()
        : produks::whereIn('id', $topIds)->get()
            ->sortBy(fn($p) => array_search($p->id, $topIds->toArray()))
            ->values();
        $drivers = Driver::limit(4)->get();
        return view('landing.index', compact('teams', 'bestSellers', 'drivers'));
    }

    public function Katalog($slug)
    {
        $team = table_teams::where('slug', $slug)->firstOrFail();

        $heroData = [
            'subtitle' => 'OFFICIAL MERCHANDISE',
            'title' => strtoupper($team->name),
            'type' => 'F1 Racing Team',
            'cover' => $team->background ?? 'images/default_bg.jpg' 
        ];
        $products = produks::where('team_slug', $slug)->get();

        return view('landing.Katalog', compact('heroData', 'products'));
    }

    public function showProduct($id)
    {
        $product = produks::findOrFail($id);
        $relatedProducts = produks::where('id', '!=', $id)->inRandomOrder()->limit(5)->get();
        return view('landing.product_detail', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $q = trim($request->input('query', ''));

        if ($q === '') {
            return response()->json([]);
        }

        // --- 1. TEAM ---
        $teams = [];
        try {
            $teams = table_teams::where('name', 'LIKE', "%{$q}%")
                ->get()
                ->map(function ($team) {
                    $slug = $team->slug ?? Str::slug($team->name);

                    return [
                        'type'  => 'Team',
                        'name'  => $team->name,
                        'image' => asset($team->logo),
                        'url'   => route('landing.Katalog', $slug),
                        'desc'  => 'Official Team',
                    ];
                })
                ->values()
                ->all();   
        } catch (\Throwable $e) {
            $teams = [];
        }

        // --- 2. DRIVER ---
        $drivers = [];
        try {
            $drivers = Driver::where('name', 'LIKE', "%{$q}%")
                ->get()
                ->map(function ($driver) {
                    $slug = $driver->team_slug;

                    if (!$slug || trim($slug) === '') {
                        $slug = Str::slug($driver->team ?? 'unknown-team');
                    }

                    $teamExists = table_teams::where('slug', $slug)->exists();
                    if (!$teamExists) {
                        $slug = 'ferrari';
                    }

                    return [
                        'type'  => 'Driver',
                        'name'  => $driver->name,
                        'image' => asset($driver->image_path ?? 'images/default-driver.png'),
                        'url'   => route('landing.Katalog', $slug),
                        'desc'  => 'Driver - ' . ($driver->team ?? '-'),
                    ];
                })
                ->values()
                ->all();  
        } catch (\Throwable $e) {
            $drivers = [];
        }

        $products = [];
        try {
            $products = produks::where('name', 'LIKE', "%{$q}%")
                ->limit(5)
                ->get()
                ->map(function ($product) {
                    return [
                        'type'  => 'Product',
                        'name'  => $product->name,
                        'image' => asset($product->cover),
                        'url'   => route('product.detail', $product->id),
                        'desc'  => 'Rp ' . number_format((float) $product->price, 0, ',', '.'),
                    ];
                })
                ->values()
                ->all();    
        } catch (\Throwable $e) {
            $products = [];
        }
        $results = array_merge($teams, $drivers, $products);

        return response()->json(collect($results)->values());
    }




    public function addToCart(Request $request)
    {
        // wajib login
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login dulu.'
            ], 401);
        }

        // VALIDASI: pakai product_id (bukan id)
        $data = $request->validate([
            'product_id' => 'required|exists:produks,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $userId    = auth()->id();
        $productId = (int) $data['product_id'];
        $qty       = (int) ($data['quantity'] ?? 1);

        // pastikan produk ada (optional karena validate sudah exists)
        $product = produks::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        // kalau sudah ada di cart → tambah qty, kalau belum → buat baru
        $item = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            CartItem::create([
                'user_id'    => $userId,
                'product_id' => $productId,
                'quantity'   => $qty,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil masuk keranjang! 🛒'
        ]);
    }

    public function getCart(Request $request)
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $items = $cartItems->map(function ($item) {
            $product = $item->product;

            // Pastikan cover jadi URL valid (kalau di DB sudah "images/xxx.jpg")
            $img = $product?->cover ? asset($product->cover) : asset('images/default.png');

            $price = (int) ($product?->price ?? 0);
            $qty   = (int) ($item->quantity ?? 0);

            return [
                // ID cart_item (dipakai untuk remove)
                'id'    => $item->id,

                // Info produk
                'name'  => $product?->name ?? '-',
                'image' => $img,

                // Qty & price buat UI
                'qty'   => $qty,
                'price' => 'Rp ' . number_format($price, 0, ',', '.'),
                'subtotal' => 'Rp ' . number_format($qty * $price, 0, ',', '.'),
            ];
        });

        $totalNumber = $cartItems->sum(function ($item) {
            return (int) $item->quantity * (int) ($item->product->price ?? 0);
        });

        // ✅ Kalau dipanggil via fetch/AJAX (Accept: application/json)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'items'   => $items->values(),
                'total'   => 'Rp ' . number_format($totalNumber, 0, ',', '.'),
            ]);
        }

        // ✅ Kalau user buka /cart/view dari browser
        return view('cart.index', [
            'cartItems' => $cartItems,
            'total'     => $totalNumber,
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart_items,id',
        ]);

        CartItem::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari keranjang.',
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'recipient_name'   => 'required|string|max:255',
            'phone'            => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'city'             => 'required|string|max:255',
            'postal_code'      => 'required|string|max:255',
            'note'             => 'nullable|string|max:255',
        ]);

        // ✅ ambil cart dari CartItem (INI YANG BENAR)
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong.');
        }

        $total = $cartItems->sum(fn ($item) =>
            $item->quantity * $item->product->price
        );

        // ✅ SIMPAN ORDER + ALAMAT
        $order = Order::create([
            'user_id'          => auth()->id(),
            'total'            => $total,
            'status'           => 'pending',
            'customer_name'    => auth()->user()->name,
            'customer_email'   => auth()->user()->email,

            // alamat pengiriman
            'recipient_name'   => $request->recipient_name,
            'phone'            => $request->phone,
            'shipping_address' => $request->shipping_address,
            'city'             => $request->city,
            'postal_code'      => $request->postal_code,
            'note'             => $request->note,
        ]);

        // simpan item order
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'qty'        => $item->quantity,
                'price' => $item->product->price,
                'subtotal'   => $item->quantity * $item->product->price,
            ]);
        }

        // kosongkan cart
        CartItem::where('user_id', auth()->id())->delete();

        return redirect()->route('pay', $order->id);
    }

    public function myOrders()
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.my', compact('orders'));
    }

    public function myOrderDetail(Order $order)
    {
        // keamanan: user hanya boleh lihat order miliknya
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['items.product']);

        return view('orders.detail', compact('order'));
    }

    public function updateCartQty(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart_items,id',
            'qty' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cartItem->quantity = $request->qty;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk diperbarui'
        ]);
    }

    public function checkoutPage()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Keranjang masih kosong.');
        }

        $total = $cartItems->sum(fn($i) => (int)$i->quantity * (int)($i->product->price ?? 0));

        return view('checkout.index', compact('cartItems', 'total'));
    }

}