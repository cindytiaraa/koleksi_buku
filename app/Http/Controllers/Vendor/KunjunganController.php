<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\LokasiToko;
use App\Models\Vendor;
use App\Models\KunjunganVendor;
use App\Models\StokToko;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    private function getVendor()
    {
        return Vendor::where('iduser', auth()->id())->firstOrFail();
    }

    public function form()
    {
        return view('vendor.kunjungan.form');
    }

    public function cekToko(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $toko = LokasiToko::find($request->barcode);
        if (!$toko) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'toko' => $toko]);
    }

    public function process(Request $request)
    {
        $vendor = $this->getVendor();

        $data = $request->validate([
            'barcode' => 'required|string|exists:lokasi_toko,barcode',
            'latitude_vendor' => 'required|numeric',
            'longitude_vendor' => 'required|numeric',
            'accuracy_vendor' => 'nullable|numeric',
        ]);

        $toko = LokasiToko::findOrFail($data['barcode']);

        $jarak = $this->haversine($toko->latitude, $toko->longitude, $data['latitude_vendor'], $data['longitude_vendor']);
        $accuracy_toko = (float) $toko->accuracy;
        $accuracy_vendor = (float) ($data['accuracy_vendor'] ?? 0);
        $threshold = 300 + $accuracy_toko + $accuracy_vendor;
        $status = $jarak <= $threshold ? 'DITERIMA' : 'DITOLAK';

        $kunjungan = KunjunganVendor::create([
            'vendor_id' => $vendor->idvendor,
            'barcode_toko' => $toko->barcode,
            'latitude_vendor' => $data['latitude_vendor'],
            'longitude_vendor' => $data['longitude_vendor'],
            'accuracy_vendor' => $accuracy_vendor,
            'jarak' => $jarak,
            'threshold_efektif' => $threshold,
            'status_kunjungan' => $status,
            'waktu_kunjungan' => now(),
        ]);

        $stocks = [];
        if ($status === 'DITERIMA') {
            $stocks = Buku::leftJoin('stok_toko', 'buku.idbuku', '=', 'stok_toko.buku_id')
                ->select('buku.idbuku', 'buku.kode', 'buku.judul', 'buku.pengarang', 'stok_toko.jumlah_stok')
                ->where('buku.status', 1)
                ->get()
                ->map(function ($item) {
                    $item->jumlah_stok = $item->jumlah_stok ?? 0;
                    return $item;
                });
        }

        return view('vendor.kunjungan.result', compact('toko', 'kunjungan', 'status', 'accuracy_toko', 'accuracy_vendor', 'threshold', 'stocks'));
    }

    public function showStore($barcode)
    {
        $vendor = $this->getVendor();
        $toko = LokasiToko::findOrFail($barcode);

        $stocks = Buku::leftJoin('stok_toko', 'buku.idbuku', '=', 'stok_toko.buku_id')
            ->select('buku.idbuku', 'buku.kode', 'buku.judul', 'buku.pengarang', 'stok_toko.jumlah_stok')
            ->where('buku.status', 1)
            ->get()
            ->map(function ($item) {
                $item->jumlah_stok = $item->jumlah_stok ?? 0;
                return $item;
            });

        return view('vendor.kunjungan.store', compact('toko', 'stocks', 'vendor'));
    }

    public function tambahStok(Request $request)
    {
        $vendor = $this->getVendor();

        $data = $request->validate([
            'barcode_toko' => 'required|string|exists:lokasi_toko,barcode',
            'buku_id' => 'required|integer|exists:buku,idbuku',
            'stok_tambah' => 'required|integer|min:1'
        ]);

        $stok = StokToko::firstOrNew([
            'barcode_toko' => $data['barcode_toko'],
            'buku_id' => $data['buku_id'],
        ]);

        $stokSebelum = $stok->exists ? $stok->jumlah_stok : 0;
        $stok->jumlah_stok = $stokSebelum + $data['stok_tambah'];
        $stok->save();

        RiwayatStok::create([
            'vendor_id' => $vendor->idvendor,
            'barcode_toko' => $data['barcode_toko'],
            'buku_id' => $data['buku_id'],
            'stok_sebelum' => $stokSebelum,
            'stok_tambah' => $data['stok_tambah'],
            'stok_sesudah' => $stok->jumlah_stok,
            'created_at' => now(),
        ]);

        return redirect()->route('vendor.kunjungan.store', ['barcode' => $data['barcode_toko']])
            ->with('success', 'Stok berhasil ditambahkan.');
    }

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371000;
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $dphi = deg2rad($lat2 - $lat1);
        $dlambda = deg2rad($lng2 - $lng1);
        $a = sin($dphi/2) * sin($dphi/2) + cos($phi1) * cos($phi2) * sin($dlambda/2) * sin($dlambda/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }
}
