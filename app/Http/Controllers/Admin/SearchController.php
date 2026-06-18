<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Vendor;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return view('admin.search.index', [
                'query' => $query,
                'results' => [],
                'total' => 0
            ]);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->limit(10)
                    ->get();

        $buku = Buku::where('judul', 'LIKE', "%{$query}%")
                   ->orWhere('pengarang', 'LIKE', "%{$query}%")
                   ->orWhere('kode', 'LIKE', "%{$query}%")
                   ->with('kategori', 'vendor')
                   ->limit(10)
                   ->get();

        $kategori = Kategori::where('nama_kategori', 'LIKE', "%{$query}%")
                           ->orWhere('kode_kategori', 'LIKE', "%{$query}%")
                           ->limit(10)
                           ->get();

        $vendors = Vendor::where('nama_vendor', 'LIKE', "%{$query}%")
                        ->orWhere('alamat', 'LIKE', "%{$query}%")
                        ->limit(10)
                        ->get();

        $results = [
            'users' => $users,
            'buku' => $buku,
            'kategori' => $kategori,
            'vendors' => $vendors
        ];

        $total = $users->count() + $buku->count() + $kategori->count() + $vendors->count();

        return view('admin.search.index', compact('query', 'results', 'total'));
    }

    public function results(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json(['results' => [], 'total' => 0]);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->select('id', 'name', 'email', 'role')
                    ->limit(5)
                    ->get()
                    ->map(function($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $user->role,
                            'type' => 'user',
                            'url' => route('admin.users.show', $user->id)
                        ];
                    });

        $buku = Buku::where('judul', 'LIKE', "%{$query}%")
                   ->orWhere('pengarang', 'LIKE', "%{$query}%")
                   ->orWhere('kode', 'LIKE', "%{$query}%")
                   ->select('id', 'judul', 'pengarang', 'harga')
                   ->limit(5)
                   ->get()
                   ->map(function($buku) {
                        return [
                            'id' => $buku->id,
                            'name' => $buku->judul,
                            'description' => $buku->pengarang,
                            'type' => 'buku',
                            'url' => route('admin.buku.show', $buku->id)
                        ];
                    });

        $kategori = Kategori::where('nama_kategori', 'LIKE', "%{$query}%")
                           ->select('id', 'nama_kategori', 'kode_kategori')
                           ->limit(5)
                           ->get()
                           ->map(function($kategori) {
                                return [
                                    'id' => $kategori->id,
                                    'name' => $kategori->nama_kategori,
                                    'description' => $kategori->kode_kategori,
                                    'type' => 'kategori',
                                    'url' => route('admin.kategori.show', $kategori->id)
                                ];
                            });

        $results = collect([...$users, ...$buku, ...$kategori]);
        $total = $results->count();

        return response()->json([
            'results' => $results->take(10),
            'total' => $total
        ]);
    }
}