<?php

namespace Tests\Feature;

use App\Models\Antrian;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Carbon\Carbon;

class AntrianTest extends TestCase
{
    use DatabaseTransactions;

    public function test_landing_page_can_be_accessed(): void
    {
        $response = $this->get(route('antrian.landing'));
        $response->assertStatus(200);
    }

    public function test_guest_page_can_be_accessed(): void
    {
        $response = $this->get(route('antrian.guest'));
        $response->assertStatus(200);
    }

    public function test_guest_can_take_queue_ticket(): void
    {
        $response = $this->post(route('antrian.ambil'), [
            'nama_pengunjung' => 'Cindy',
        ]);

        $this->assertDatabaseHas('antrian', [
            'nama_pengunjung' => 'Cindy',
            'kode_antrian' => 'A001',
            'status' => 'menunggu',
            'tanggal_antrian' => Carbon::today()->toDateString(),
        ]);

        $antrian = Antrian::first();
        $response->assertRedirect(route('antrian.tiket', $antrian->id));
    }

    public function test_guest_can_take_queue_ticket_via_ajax(): void
    {
        $response = $this->postJson(route('antrian.ambil'), [
            'nama_pengunjung' => 'Bob',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'kode' => 'A001',
        ]);

        $this->assertDatabaseHas('antrian', [
            'nama_pengunjung' => 'Bob',
            'kode_antrian' => 'A001',
        ]);
    }

    public function test_papan_antrian_page_can_be_accessed(): void
    {
        $response = $this->get(route('antrian.papan'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_dashboard_without_auth(): void
    {
        $response = $this->get(route('antrian.admin'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_cannot_access_dashboard_without_otp(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('antrian.admin'));

        $response->assertRedirect(route('otp.form'));
    }

    public function test_admin_can_access_dashboard_with_auth_and_otp(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['otp_verified' => true])
            ->get(route('antrian.admin'));

        $response->assertStatus(200);
    }

    public function test_admin_can_call_next_queue(): void
    {
        $user = User::factory()->create();
        
        $antrian1 = Antrian::create([
            'kode_antrian' => 'A001',
            'nama_pengunjung' => 'Cindy',
            'status' => 'menunggu',
            'tanggal_antrian' => Carbon::today()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_verified' => true])
            ->post(route('antrian.panggil'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'antrian' => [
                'id' => $antrian1->id,
                'kode_antrian' => 'A001',
                'nama_pengunjung' => 'Cindy',
            ]
        ]);

        $this->assertDatabaseHas('antrian', [
            'id' => $antrian1->id,
            'status' => 'dipanggil',
        ]);
    }

    public function test_admin_can_skip_queue(): void
    {
        $user = User::factory()->create();
        
        $antrian = Antrian::create([
            'kode_antrian' => 'A001',
            'nama_pengunjung' => 'Cindy',
            'status' => 'menunggu',
            'tanggal_antrian' => Carbon::today()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_verified' => true])
            ->post(route('antrian.skip', $antrian->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Antrian di-skip.',
        ]);

        $this->assertDatabaseHas('antrian', [
            'id' => $antrian->id,
            'status' => 'selesai',
        ]);
    }

    public function test_admin_can_mark_queue_late(): void
    {
        $user = User::factory()->create();
        
        $antrian = Antrian::create([
            'kode_antrian' => 'A001',
            'nama_pengunjung' => 'Cindy',
            'status' => 'dipanggil',
            'tanggal_antrian' => Carbon::today()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_verified' => true])
            ->post(route('antrian.terlambat', $antrian->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Antrian ditandai terlambat.',
        ]);

        $this->assertDatabaseHas('antrian', [
            'id' => $antrian->id,
            'status' => 'terlambat',
        ]);
    }

    public function test_admin_can_recall_queue(): void
    {
        $user = User::factory()->create();
        
        $antrian = Antrian::create([
            'kode_antrian' => 'A001',
            'nama_pengunjung' => 'Cindy',
            'status' => 'terlambat',
            'tanggal_antrian' => Carbon::today()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_verified' => true])
            ->post(route('antrian.panggil_ulang', $antrian->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'antrian' => [
                'id' => $antrian->id,
                'kode_antrian' => 'A001',
            ]
        ]);

        $this->assertDatabaseHas('antrian', [
            'id' => $antrian->id,
            'status' => 'dipanggil',
        ]);
    }

    public function test_admin_can_reset_daily_queue(): void
    {
        $user = User::factory()->create();
        
        $antrian = Antrian::create([
            'kode_antrian' => 'A001',
            'nama_pengunjung' => 'Cindy',
            'status' => 'menunggu',
            'tanggal_antrian' => Carbon::today()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_verified' => true])
            ->post(route('antrian.reset'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Antrian hari ini telah direset.',
        ]);

        $this->assertDatabaseHas('antrian', [
            'id' => $antrian->id,
            'status' => 'selesai',
        ]);
    }
}
