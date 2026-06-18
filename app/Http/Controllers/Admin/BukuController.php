<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Http\Controllers\Controller;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Buku::with('kategori')->get();
        return view('admin.buku.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.buku.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'idkategori' => 'required'
        ]);

        $kategori = Kategori::find($request->idkategori);

        $jumlahBuku = Buku::where('idkategori', $kategori->idkategori)->count() + 1;

        $kodeBuku = $kategori->kode_kategori . '-' . str_pad($jumlahBuku, 3, '0', STR_PAD_LEFT);

        Buku::create([
            'kode' => $kodeBuku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'idkategori' => $request->idkategori,
            'status' => 1 // otomatis aktif
        ]);


        return redirect()->route('admin.buku.index')
                ->with('success','Buku berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Buku::with('kategori')->findOrFail($id);
        
        // QR Code berisi URL lengkap agar bisa di-scan pakai HP biasa
        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(route('admin.buku.show', $data->idbuku));

        return view('admin.buku.show', compact('data', 'qrcode'));
    }

    public function cetakQr($id)
    {
        $data = Buku::findOrFail($id);
        // Ukuran 250px cukup untuk PDF kecil
        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(250)->margin(1)->generate(route('admin.buku.show', $data->idbuku));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.buku.cetak_qr', compact('data', 'qrcode'))
                    ->setPaper([0, 0, 200, 250]); 

        return $pdf->download('QR_Buku_'.$data->kode.'.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Buku::findOrFail($id);
        $kategori = Kategori::all();
        return view('admin.buku.edit', compact('data','kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Buku::findOrFail($id);

        $data->update([
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'harga' => $request->harga,
            'idkategori' => $request->idkategori,
            'status' => $request->all()['status'] == 'tersedia' ? 1 : 0,
        ]);

        return redirect()->route('admin.buku.index')
                ->with('success','Buku berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Buku::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.buku.index')
                         ->with('success','Data buku berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $buku = Buku::findOrFail($id);

        $buku->status = $buku->status == 1 ? 0 : 1;
        $buku->save();

        return redirect()->route('admin.buku.index')
                        ->with('success','Status buku berhasil diperbarui');
    }

    public function datatablesView()
    {
        $buku = Buku::with('kategori')->get();
        $kategori = Kategori::all();
        return view('admin.datatables.index', compact('buku', 'kategori'));
    }

    public function cetak(Request $request)
    {
        $ids = $request->buku_ids;
        $x = $request->x;
        $y = $request->y;

        $buku = Buku::whereIn('idbuku',$ids)->get();

        $pdf = PDF::loadView('admin.tag.cetak', compact('buku','x','y'));

        return $pdf->download('label_buku.pdf');

    }
    public function selectView()
    {
        $kategori = Kategori::with('buku')->get();
        return view('admin.buku.select', compact('kategori'));
    }

}