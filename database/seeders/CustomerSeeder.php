<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create 10 dummy customers
        for ($i = 1; $i <= 10; $i++) {
            $nama = 'Customer ' . $i;
            $alamat = $i . ' Jalan Example, No. ' . $i;
            $provinsi = 'Provinsi ' . $i;
            $kota = 'Kota ' . $i;
            $kecamatan = 'Kecamatan ' . $i;
            $kodepos = '1' . str_pad($i, 4, '0', STR_PAD_LEFT);

            // Generate a simple placeholder image (1x1 pixel) as base64
            $placeholderBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAukB9nK7Y+QAAAAASUVORK5CYII=';
            $placeholderBinary = base64_decode($placeholderBase64);

            Customer::create([
                'nama' => $nama,
                'alamat' => $alamat,
                'provinsi' => $provinsi,
                'kota' => $kota,
                'kecamatan' => $kecamatan,
                'kodepos' => $kodepos,
                // Store base64 string in foto_blob to avoid charset issues
                'foto_blob' => $placeholderBase64,
                // Store the binary image file for foto_path
                'foto_path' => 'customers/placeholder_' . $i . '.png',
            ]);

            // Store the placeholder image file for foto_path
            Storage::put('public/customers/placeholder_' . $i . '.png', $placeholderBinary);
        }
    }
}
?>
