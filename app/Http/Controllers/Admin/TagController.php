<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TagController extends Controller
{

    public function index()
    {
        $buku = Buku::where('status',1)->get();

        return view('admin.tag.index', compact('buku'));
    }

    public function cetak(Request $request)
    {
        $selectedIds = $request->input('buku', []);
        $startX = (int)$request->input('x', 1);
        $startY = (int)$request->input('y', 1);

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu buku');
        }

        // Get books with category relation
        $buku = Buku::with('kategori')->whereIn('idbuku', $selectedIds)->get();
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

        // Map books with barcodes and other necessary data
        $items = $buku->map(function ($b) use ($generator) {
            $b->barcode = base64_encode(
                $generator->getBarcode($b->kode, $generator::TYPE_CODE_128)
            );
            return $b;
        })->toArray();

        // Calculate starting position (0-based)
        // Standard TJ 108: 5 columns, 8 rows
        // X = Column (1-5), Y = Row (1-8)
        $startIndex = (($startY - 1) * 5) + ($startX - 1);

        // Fill empty slots BEFORE the first selected item
        $allSlots = array_fill(0, $startIndex, null);
        $allSlots = array_merge($allSlots, $items);

        // Chunk into pages of exactly 40 slots (5 columns x 8 rows)
        $pages = array_chunk($allSlots, 40);

        $pdf = Pdf::loadView('admin.tag.cetak', compact('pages'))
                    ->setPaper('a4', 'portrait');

        return $pdf->download('label_harga_buku.pdf');
    }

}