<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Vendor;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function index()
    {
        $vendor = Vendor::all();
        return view('admin.order.index', compact('vendor'));
    }

    // AJAX: cari buku by vendor
    public function cariBuku(Request $request)
    {
        $buku = Buku::where('idvendor', $request->idvendor)
                    ->where('status', 1)
                    ->get()
                    ->map(fn($b) => [
                        'kode'  => $b->kode,
                        'nama'  => $b->judul,
                        'harga' => $b->harga ?? 0,
                    ]);

        return response()->json([
            'status' => 'success',
            'code'   => 200,
            'data'   => $buku
        ]);
    }

    // POST: checkout & buat transaksi Midtrans
    public function checkout(Request $request)
    {
        $items      = $request->items;
        $total      = $request->total;
        $metodeBayar= $request->metode_bayar; // 1=VA, 2=QRIS

        if (!$items || count($items) === 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Keranjang kosong'
            ], 400);
        }

        // Generate nama guest otomatis
        $lastPesanan = Pesanan::latest('idpesanan')->first();
        $nomorGuest  = $lastPesanan
            ? intval(substr($lastPesanan->nama, 6)) + 1
            : 1;
        $namaGuest   = 'Guest_' . str_pad($nomorGuest, 7, '0', STR_PAD_LEFT);

        // Simpan pesanan
        $orderId  = 'ORDER-' . time() . '-' . rand(100, 999);
        $pesanan  = Pesanan::create([
            'nama'              => $namaGuest,
            'total'             => $total,
            'metode_bayar'      => $metodeBayar,
            'status_bayar'      => 0,
            'midtrans_order_id' => $orderId,
        ]);

        // Simpan detail
        foreach ($items as $item) {
            DetailPesanan::create([
                'idpesanan' => $pesanan->idpesanan,
                'kode_buku' => $item['kode'],
                'jumlah'    => $item['jumlah'],
                'harga'     => $item['harga'],
                'subtotal'  => $item['subtotal'],
                'catatan'   => $item['catatan'] ?? null,
            ]);
        }

        // Setup Midtrans
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // Item details untuk Midtrans
        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id'       => $item['kode'],
                'price'    => $item['harga'],
                'quantity' => $item['jumlah'],
                'name'     => substr($item['nama'], 0, 50),
            ];
        }

        // Payment type
        $enabledPayments = $metodeBayar == 1
            ? ['bni_va', 'bri_va', 'bca_va', 'permata_va']
            : ['gopay', 'qris'];

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $namaGuest,
            ],
            'item_details'   => $itemDetails,
            'enabled_payments' => $enabledPayments,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan snap token
            $pesanan->snap_token = $snapToken;
            $pesanan->save();

            return response()->json([
                'status'     => 'success',
                'code'       => 200,
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
                'nama_guest' => $namaGuest,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    // Halaman status pesanan
    public function status($midtrans_order_id)
    {
        $pesanan = Pesanan::where('midtrans_order_id', $midtrans_order_id)
                          ->with('detail')
                          ->firstOrFail();
        return view('admin.order.status', compact('pesanan'));
    }

    // Callback untuk update status bayar (jika tidak pakai Midtrans Notification)
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $signatureKey = hash("sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signatureKey != $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pesanan = Pesanan::where('midtrans_order_id', $request->order_id)->first();

        if ($request->transaction_status == 'settlement') {
            $pesanan->status_bayar = 1; // LUNAS
            $pesanan->save();
        }

        return response()->json(['message' => 'OK']);
    }
}