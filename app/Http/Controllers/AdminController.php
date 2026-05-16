<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\produks;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Basic counts
        $totalUsers  = \App\Models\User::count();
        $totalProduk = \App\Models\produks::count();

        $totalOrders     = Order::count();
        $totalCustomers  = Order::distinct('user_id')->count('user_id');
        $pendingOrders   = Order::where('status', 'pending')->count();

        // Total sales
        $totalSales = (int) Order::sum('total');

        // Top products
        $topProducts = OrderItem::query()
            ->join('produks', 'produks.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', ['approved', 'processing', 'shipped', 'delivered'])
            ->select([
                'produks.id',
                'produks.name',
                'produks.category',
                DB::raw('SUM(order_items.qty) AS sold'),
                DB::raw('SUM(order_items.subtotal) AS revenue'),
            ])
            ->groupBy('produks.id', 'produks.name', 'produks.category')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        // Stock report
        $stockReport = \App\Models\produks::query()
            ->select(['id', 'name', 'category', 'stock'])
            ->orderBy('stock', 'asc')
            ->limit(8)
            ->get()
            ->map(function ($p) {
                $st = (int)$p->stock;
                $p->status = $st <= 0 ? 'Habis' : ($st <= 5 ? 'Menipis' : 'Aman');
                return $p;
            });

        // ==========================
        // DATA GRAFIK (7 hari terakhir)
        // ==========================
        $days = 7;
        $startDate = Carbon::today()->subDays($days - 1);

        $sales = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->whereDate('created_at', '>=', $startDate)
            // kalau mau hanya order sukses, aktifkan ini:
            ->whereIn('status', ['approved', 'processing', 'shipped', 'delivered'])
            ->groupBy('date')
            ->pluck('total', 'date'); // hasil: ['2025-01-01' => 12345, ...]

        $labels = [];
        $data   = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d M');
            $data[]   = (int) ($sales[$date] ?? 0);
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProduk',
            'totalOrders',
            'totalCustomers',
            'pendingOrders',
            'totalSales',
            'topProducts',
            'stockReport',
            'labels',
            'data'
        ));
    }


    public function ordersIndex(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $ordersDb = Order::query()
            ->with(['items.product'])
            ->when($q, function ($query) use ($q) {
                $query->where('order_code', 'like', "%{$q}%")
                    ->orWhere('id', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_email', 'like', "%{$q}%");
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->get();

        // statistik status
        $pendingCount = Order::where('status', 'pending')->count();
        $processCount = Order::whereIn('status', ['approved', 'processing', 'shipped'])->count();
        $completedCount    = Order::where('status', 'delivered')->count();

        $orders = $ordersDb->map(function ($o) {
            $total = (int) ($o->total ?? 0);

            // badge bootstrap biar sama dengan CSS kamu
            $badge = match ($o->status ?? 'pending') {
                'approved'   => '<span class="badge bg-primary">Approved</span>',
                'processing' => '<span class="badge bg-info text-dark">Processing</span>',
                'shipped'    => '<span class="badge bg-secondary">Shipped</span>',
                'delivered'  => '<span class="badge bg-success">Delivered</span>',
                'cancelled'  => '<span class="badge bg-danger">Cancelled</span>',
                default      => '<span class="badge bg-warning text-dark">Pending</span>',
            };
            $payBadge = match ($o->payment_status ?? 'pending') {
                'paid'    => '<span class="badge bg-success">Paid</span>',
                'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
                'expired' => '<span class="badge bg-secondary">Expired</span>',
                'failed'  => '<span class="badge bg-danger">Failed</span>',
                default   => '<span class="badge bg-secondary">' . e($o->payment_status) . '</span>',
            };

            $items = $o->items->map(function ($it) {
                $pname = optional($it->product)->name ?? 'Produk';

                $qty   = (int) ($it->qty ?? 0);
                $sub   = (int) ($it->subtotal ?? 0);

                $productPrice = (int) (optional($it->product)->price ?? 0);
                $unitPrice = $productPrice > 0
                    ? $productPrice
                    : ($qty > 0 ? (int) round($sub / $qty) : 0);

                return [
                    'name'     => $pname,
                    'qty'      => $qty,
                    'price'    => 'Rp ' . number_format($unitPrice, 0, ',', '.'),
                    'subtotal' => 'Rp ' . number_format($sub, 0, ',', '.'),
                ];
            })->values()->all();

            return [
                'id' => $o->id,
                'code' => $o->order_code ?? ("ORD-" . $o->id),
                'customer_name' => $o->customer_name ?? 'Customer',
                'customer_email' => $o->customer_email ?? null,
                'recipient_name' => $o->recipient_name ?? null,
                'phone'          => $o->phone ?? null,
                'address'        => trim(implode(', ', array_filter([
                    $o->shipping_address ?? null,
                    $o->city ?? null,
                    $o->postal_code ?? null,
                ]))),
                'note'           => $o->note ?? null,
                'date' => optional($o->created_at)->format('d M Y, H:i'),
                'total' => 'Rp ' . number_format($total, 0, ',', '.'),
                'status' => $o->status ?? 'pending',
                'status_badge_html' => $badge,
                'payment_status' => $o->payment_status ?? 'pending',
                'payment_badge_html' => $payBadge,
                'items' => $items,
            ];
        });
        $days = 7;
        $startDate = Carbon::today()->subDays($days - 1);

        // ambil total penjualan per hari
        $sales = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        // isi labels & data secara PAKSA 7 hari
        $labels = [];
        $data   = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d M');
            $data[]   = (int) ($sales[$date] ?? 0);
        }

        // return view('admin.orders.index', compact(
        //     'orders','pendingCount','processCount','completedCount'
        // ));
        return view('admin.orders.index', compact(
            'orders',
            'pendingCount',
            'processCount',
            'completedCount'
        ));
    }

    public function produkIndex(Request $request)
    {
        $q = produks::query();

        // filter kategori
        if ($request->filled('category')) {
            $q->where('category', $request->category);
        }

        // filter tim
        if ($request->filled('team_slug')) {
            $q->where('team_slug', $request->team_slug);
        }

        // pencarian
        if ($request->filled('search')) {
            $q->where('name', 'like', '%' . $request->search . '%');
        }

        $produksList = $q->latest()->paginate(8)->withQueryString();

        // statistik
        $totalProduk = produks::count();
        $stokRendah  = produks::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $stokHabis   = produks::where('stock', '<=', 0)->count();

        $categories = produks::select('category')->distinct()->orderBy('category')->pluck('category');
        $teamOptions = [
            'ferrari' => 'Ferrari',
            'mercedes' => 'Mercedes',
            'red-bull-racing' => 'Red Bull Racing',
            'mclaren' => 'McLaren',
            'aston-martin' => 'Aston Martin',
            'alpine' => 'Alpine',
            'williams-racing' => 'Williams',
            'haas' => 'Haas',
            'sauber' => 'Sauber',
            'racingbulls-rb' => 'RB',
        ];
        $teams = collect($teamOptions);


        return view('admin.produk.index', [
            'produks' => $produksList,
            'totalProduk' => $totalProduk,
            'stokRendah' => $stokRendah,
            'stokHabis' => $stokHabis,
            'categories' => $categories,
            'teams' => $teams,
        ]);
    }

    public function produkStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'team_slug'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('cover');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/produk'), $filename);

        $validated['cover'] = 'images/produk/' . $filename;

        produks::create($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function produkUpdate(Request $request, $id)
    {
        $produk = produks::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'team_slug'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // jika upload cover baru
        if ($request->hasFile('cover')) {

            // 1) hapus cover lama (kalau memang file-nya ada)
            if (!empty($produk->cover)) {
                $oldPath = public_path($produk->cover); // contoh: public/images/produk/abc.jpg

                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // 2) simpan cover baru ke public/images/produk
            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/produk'), $filename);

            // 3) simpan path ke DB (format images/produk/xxx.jpg)
            $validated['cover'] = 'images/produk/' . $filename;
        }

        $produk->update($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function produkDestroy($id)
    {
        $produk = produks::findOrFail($id);

        // hapus file cover kalau di storage
        if ($produk->cover && str_starts_with($produk->cover, 'storage/')) {
            $old = str_replace('storage/', '', $produk->cover);
            Storage::disk('public')->delete($old);
        }

        $produk->delete();

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function usersIndex()
    {
        $users = User::orderByDesc('id')->get();
        return view('admin.users.index', compact('users'));
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,customer,superadmin,staff,viewer',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function usersUpdateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,customer,superadmin,staff,viewer'
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Role user berhasil diupdate!');
    }

    public function usersDestroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }


    public function ordersUpdateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);


        return response()->json([
            'success' => true,
            'status' => $order->status,
        ]);
    }
}
