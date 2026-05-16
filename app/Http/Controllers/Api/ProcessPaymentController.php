<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class ProcessPaymentController extends Controller
{
    public function pay(Order $order)
    {
        // 🔐 keamanan: hanya pemilik order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // ❌ kalau sudah dibayar, tidak boleh bayar lagi
        if ($order->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Pesanan sudah dibayar');
        }

        // 🔧 konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 🧾 pastikan order_code unik GLOBAL
        if (!$order->order_code) {
            $order->order_code = 'ORD-' . Str::uuid();
            $order->save();
        }

        // 🔁 buat snap token hanya jika perlu
        if (
            !$order->snap_token ||
            in_array($order->payment_status, ['failed', 'expired'])
        ) {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $order->total,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];

            $order->snap_token = Snap::getSnapToken($params);
            $order->payment_status = 'pending';
            $order->save();
        }

        return view('payments.pay', compact('order'));
    }

    public function isPayed(Order $order)
    {
        $order->payment_status = 'paid';
        $order->save();

        return redirect()->route('orders.my');
    }

    // 🔔 WEBHOOK MIDTRANS
    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('MIDTRANS WEBHOOK', $payload);

        $order = Order::where('order_code', $payload['order_id'])->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        switch ($payload['transaction_status']) {
            case 'settlement':
            case 'capture':
                $order->payment_status = 'paid';
                $order->status = 'approved';
                break;

            case 'pending':
                $order->payment_status = 'pending';
                break;

            case 'expire':
                $order->payment_status = 'expired';
                break;

            case 'cancel':
            case 'deny':
                $order->payment_status = 'failed';
                break;
        }

        $order->save();

        return response()->json(['success' => true]);
    }
}
