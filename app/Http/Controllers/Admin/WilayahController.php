<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function index()
    {
        return view('admin.wilayah.index');
    }

    public function provinsi()
    {
        $response = Http::get('https://wilayah.id/api/provinces.json');
        return response()->json($response->json());
    }

    public function kotaByName($name)
    {
        // Fetch provinces first to find matching code
        $provincesResponse = Http::get('https://wilayah.id/api/provinces.json');
        if (!$provincesResponse->successful()) {
            return response()->json(['data' => []]);
        }

        $provinces = $provincesResponse->json()['data'] ?? [];
        $provinceCode = null;
        foreach ($provinces as $prov) {
            if (strcasecmp($prov['name'], $name) === 0) {
                $provinceCode = $prov['code'];
                break;
            }
        }

        if (!$provinceCode) {
            return response()->json(['data' => []]);
        }

        $regenciesResponse = Http::get("https://wilayah.id/api/regencies/{$provinceCode}.json");
        return response()->json($regenciesResponse->json());
    }
}