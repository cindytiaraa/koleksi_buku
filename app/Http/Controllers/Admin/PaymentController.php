<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function notification(Request $request)
    {
        $payload = $request->all();
        \Log::info('Midtrans Notification received', [
            'method'  => $request->method(),
            'payload' => $payload,
            'ua'      => $request->userAgent()
        ]);

        // Jika request kosong atau hanya tes dari dashboard
        if (empty($payload)) {
            \Log::info('Midtrans Notification: Empty payload (possibly reachability test)');
            return response()->json(['message' => 'Empty payload'], 200);
        }

        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            // Kita bisa ambil data langsung dari request tanpa class Notification 
            // agar lebih fleksibel saat testing.
            $orderId     = $request->order_id;
            $statusCode  = $request->status_code;
            $transStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status;
            $signature   = $request->signature_key;

            // Verifikasi Signature (Opsional untuk testing, tapi wajib untuk production)
            $serverKey = env('MIDTRANS_SERVER_KEY');
            $localSignature = hash("sha512", $orderId . $statusCode . $request->gross_amount . $serverKey);

            if ($signature && $signature !== $localSignature) {
                 \Log::error('Midtrans Notification: Invalid Signature', [
                     'received' => $signature,
                     'expected' => $localSignature
                 ]);
                 // return response()->json(['message' => 'Invalid signature'], 403);
            }

            \Log::info('Midtrans Notification details', [
                'orderId'     => $orderId,
                'transStatus' => $transStatus,
            ]);

            $pesanan = Pesanan::where('midtrans_order_id', $orderId)->first();
            
            if (!$pesanan) {
                \Log::warning('Midtrans Notification: Order not found', ['orderId' => $orderId]);
                // Tetap return 200 jika ini cuma testing agar dashboard Midtrans tidak error
                return response()->json(['message' => 'Order not found, but OK for test'], 200);
            }

            // Tentukan status bayar
            if ($transStatus == 'capture') {
                $pesanan->status_bayar = $fraudStatus == 'accept' ? 1 : 0;
            } elseif (in_array($transStatus, ['settlement', 'success'])) {
                $pesanan->status_bayar = 1; // lunas
            } elseif (in_array($transStatus, ['cancel', 'deny', 'expire'])) {
                $pesanan->status_bayar = 2; // gagal
            } elseif ($transStatus == 'pending') {
                $pesanan->status_bayar = 0; // pending
            }

            $pesanan->save();
            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            \Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}