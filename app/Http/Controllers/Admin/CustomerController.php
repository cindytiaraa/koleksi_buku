<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Data Customer — tabel semua customer
     */
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customer.index', compact('customers'));
    }

    /**
     * Tambah Customer 1 — foto disimpan sebagai BLOB
     */
    public function tambah1()
    {
        return view('admin.customer.tambah1');
    }

    /**
     * Simpan Customer 1 — foto sebagai BLOB (base64 dari kamera)
     */
    public function simpan1(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'foto_b64'=> 'required|string', // base64 dari canvas
        ]);

        // base64 datanya: "data:image/png;base64,xxxx"
        // Simpan langsung string base64-nya ke kolom longText
        Customer::create([
            'nama'             => $request->nama,
            'alamat'           => $request->alamat,
            'provinsi'         => $request->provinsi,
            'kota'             => $request->kota,
            'kecamatan'        => $request->kecamatan,
            'kodepos_kelurahan'=> $request->kodepos_kelurahan,
            'foto_blob'        => $request->foto_b64, // simpan full base64 string
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data customer (blob) berhasil disimpan',
        ]);
    }

    /**
     * Tambah Customer 2 — foto disimpan sebagai file
     */
    public function tambah2()
    {
        return view('admin.customer.tambah2');
    }

    /**
     * Simpan Customer 2 — foto sebagai file, path di DB
     */
    public function simpan2(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'foto_b64'=> 'required|string',
        ]);

        // Decode base64 → simpan sebagai file PNG
        $base64  = $request->foto_b64;
        $imgData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imgData = base64_decode($imgData);

        $filename = 'customer_' . time() . '_' . uniqid() . '.png';
        $path     = 'customer/' . $filename;

        Storage::disk('public')->put($path, $imgData);

        Customer::create([
            'nama'             => $request->nama,
            'alamat'           => $request->alamat,
            'provinsi'         => $request->provinsi,
            'kota'             => $request->kota,
            'kecamatan'        => $request->kecamatan,
            'kodepos_kelurahan'=> $request->kodepos_kelurahan,
            'foto_path'        => $path, // simpan path file
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data customer (file) berhasil disimpan',
        ]);
    }
}
