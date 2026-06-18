<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    /**
     * Display a basic page with links for PDF generation.
     */
    public function index()
    {
        // simple view just showing links available below sidebar
        return view('admin.pdf.index');
    }
    
    public function sertifikat()
    {
        $data = [
            'nama' => 'Cindy Tiara Anastasya',
            'kegiatan' => 'Workshop Laravel'
        ];

        $pdf = Pdf::loadView('admin.pdf.sertifikat', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat.pdf');

    }

    public function undangan()
    {
        $data = [
            'judul' => 'Pengumuman Fakultas',
            'isi' => 'Diberitahukan kepada seluruh mahasiswa bahwa ...'
        ];

        $pdf = Pdf::loadView('admin.pdf.undangan', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download('undangan.pdf');
    }

    public function cetak(Request $request)
    {
        $ids = $request->buku_ids;
        $x = $request->x;
        $y = $request->y;

        $buku = Buku::whereIn('idbuku',$ids)->get();

        $pdf = PDF::loadView('admin.buku.label',compact('buku','x','y'));

        return $pdf->download('label_buku.pdf');
    }
    
}