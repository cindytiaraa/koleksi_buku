<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LokasiToko;
use App\Models\KunjunganToko;
use Illuminate\Support\Str;

class KunjunganTokoController extends Controller
{
    public function indexToko()
    {
        $tokos = LokasiToko::orderBy('nama_toko')->get();
        return view('admin.toko.index', compact('tokos'));
    }

    public function createToko()
    {
        return view('admin.toko.create');
    }

    public function storeToko(Request $request)
    {
        $data = $request->validate([
            'nama_toko' => 'required|string|max:191',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric'
        ]);

        // generate simple unique barcode
        $barcode = strtoupper('TK'.time().Str::random(4));

        $lokasi = LokasiToko::create([
            'barcode' => $barcode,
            'nama_toko' => $data['nama_toko'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'accuracy' => $data['accuracy'] ?? 0,
        ]);

        return redirect()->route('admin.toko.index')->with('success', 'Toko tersimpan. Barcode: '.$barcode);
    }

    public function printBarcode($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);
        return view('admin.toko.print', compact('toko'));
    }

    public function kunjunganForm()
    {
        return view('admin.kunjungan.form');
    }

    // API: ambil data toko by barcode (JSON)
    public function apiGetToko($barcode)
    {
        $toko = LokasiToko::find($barcode);
        if (!$toko) {
            return response()->json(['success' => false], 404);
        }
        return response()->json(['success' => true, 'toko' => $toko]);
    }

    public function processKunjungan(Request $request)
    {
        $data = $request->validate([
            'barcode' => 'required|string|exists:lokasi_toko,barcode',
            'latitude_sales' => 'required|numeric',
            'longitude_sales' => 'required|numeric',
            'accuracy_sales' => 'nullable|numeric',
        ]);

        $toko = LokasiToko::findOrFail($data['barcode']);

        $lat1 = (float) $toko->latitude;
        $lng1 = (float) $toko->longitude;
        $lat2 = (float) $data['latitude_sales'];
        $lng2 = (float) $data['longitude_sales'];

        $jarak = $this->haversine($lat1, $lng1, $lat2, $lng2);
        $accuracy_toko = (float) ($toko->accuracy ?? 0);
        $accuracy_sales = (float) ($data['accuracy_sales'] ?? 0);

        $threshold = 300 + $accuracy_toko + $accuracy_sales;

        $status = $jarak <= $threshold ? 'DITERIMA' : 'DITOLAK';

        $kunjungan = KunjunganToko::create([
            'barcode_toko' => $toko->barcode,
            'latitude_sales' => $lat2,
            'longitude_sales' => $lng2,
            'accuracy_sales' => $accuracy_sales,
            'jarak' => $jarak,
            'status_kunjungan' => $status,
            'created_at' => now(),
        ]);

        return view('admin.kunjungan.result', compact('toko','kunjungan','jarak','threshold','accuracy_toko','accuracy_sales','status'));
    }

    public function indexKunjungan()
    {
        $kunjungans = \App\Models\KunjunganVendor::with(['toko','vendor'])->orderBy('waktu_kunjungan', 'desc')->get();
        return view('admin.kunjungan.index', compact('kunjungans'));
    }

    public function indexStok()
    {
        $stores = LokasiToko::withCount('stok')->orderBy('nama_toko')->get();
        return view('admin.stok.index', compact('stores'));
    }

    public function showStok($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        $stocks = \App\Models\Buku::leftJoin('stok_toko', function($join) use ($barcode) {
            $join->on('buku.idbuku', '=', 'stok_toko.buku_id')
                 ->where('stok_toko.barcode_toko', $barcode);
        })
        ->select('buku.idbuku', 'buku.kode', 'buku.judul', 'buku.pengarang', 'stok_toko.jumlah_stok')
        ->where('buku.status', 1)
        ->orderBy('buku.judul')
        ->get()
        ->map(function($item) {
            $item->jumlah_stok = $item->jumlah_stok ?? 0;
            return $item;
        });

        return view('admin.stok.show', compact('toko', 'stocks'));
    }

    // Haversine formula returns meters
    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371000; // meters
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $dphi = deg2rad($lat2 - $lat1);
        $dlambda = deg2rad($lng2 - $lng1);

        $a = sin($dphi/2) * sin($dphi/2) + cos($phi1) * cos($phi2) * sin($dlambda/2) * sin($dlambda/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $R * $c;
    }
}
