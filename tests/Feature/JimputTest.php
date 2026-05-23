<?php

namespace Tests\Feature;

use App\Models\JimpitanMasuk;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JimputTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $petugas;
    protected Warga $warga;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin
        $this->admin = User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // Buat user petugas
        $this->petugas = User::create([
            'name'     => 'Petugas Test',
            'email'    => 'petugas@test.com',
            'username' => 'petugas',
            'password' => Hash::make('password123'),
            'role'     => 'petugas',
        ]);

        // Buat warga aktif
        $this->warga = Warga::create([
            'nama_warga' => 'Budi Warga',
            'no_rumah'   => '10',
            'rt_rw'      => 'RT 01/RW 02',
            'qr_token'   => 'token-budi-12345',
            'aktif'      => true,
        ]);
    }

    // ── 1. AUTHENTICATION TESTS ───────────────────────────────────

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/scanner');
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Jimput');
    }

    public function test_petugas_can_login_and_redirects_to_scanner()
    {
        $response = $this->post('/login', [
            'username' => 'petugas',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('scanner'));
        $this->assertAuthenticatedAs($this->petugas);
    }

    public function test_admin_can_login_and_redirects_to_dashboard()
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_login_validation_errors()
    {
        $response = $this->post('/login', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['username', 'password']);
    }

    public function test_incorrect_credentials_fail_login()
    {
        $response = $this->post('/login', [
            'username' => 'petugas',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $this->actingAs($this->petugas);
        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    // ── 2. WARGA CRUD TESTS (ADMIN ONLY) ──────────────────────────

    public function test_non_admin_cannot_access_warga_management()
    {
        $this->actingAs($this->petugas);

        $response = $this->get('/admin/wargas');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_warga_management()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/wargas');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Warga');
        $response->assertSee($this->warga->nama_warga);
    }

    public function test_admin_can_create_new_warga()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/wargas', [
            'nama_warga' => 'Warga Baru',
            'no_rumah'   => '15',
            'rt_rw'      => 'RT 02/RW 02',
        ]);

        $response->assertRedirect(route('admin.wargas.index'));
        $this->assertDatabaseHas('wargas', [
            'nama_warga' => 'Warga Baru',
            'no_rumah'   => '15',
        ]);
    }

    public function test_admin_can_update_warga()
    {
        $this->actingAs($this->admin);

        $response = $this->put("/admin/wargas/{$this->warga->id}", [
            'nama_warga' => 'Budi Warga Updated',
            'no_rumah'   => '10B',
            'rt_rw'      => 'RT 01/RW 02',
            'aktif'      => 1,
        ]);

        $response->assertRedirect(route('admin.wargas.index'));
        $this->assertDatabaseHas('wargas', [
            'id'         => $this->warga->id,
            'nama_warga' => 'Budi Warga Updated',
            'no_rumah'   => '10B',
        ]);
    }

    public function test_admin_can_delete_warga()
    {
        $this->actingAs($this->admin);

        $response = $this->delete("/admin/wargas/{$this->warga->id}");

        $response->assertRedirect(route('admin.wargas.index'));
        $this->assertDatabaseMissing('wargas', [
            'id' => $this->warga->id,
        ]);
    }

    public function test_admin_can_regenerate_qr_token()
    {
        $this->actingAs($this->admin);
        $oldToken = $this->warga->qr_token;

        $response = $this->post("/admin/wargas/{$this->warga->id}/regenerate-qr");

        $response->assertRedirect(route('admin.wargas.index'));
        
        $this->warga->refresh();
        $this->assertNotEquals($oldToken, $this->warga->qr_token);
        $this->assertStringStartsWith('token-warga-', $this->warga->qr_token);
    }

    public function test_admin_can_view_cetak_qr_page()
    {
        $this->actingAs($this->admin);

        $response = $this->get("/admin/wargas/{$this->warga->id}/cetak");

        $response->assertStatus(200);
        $response->assertSee('KARTU JIMPITAN WARGA');
        $response->assertSee($this->warga->nama_warga);
    }

    // ── 3. SCANNING & API TESTS ───────────────────────────────────

    public function test_authenticated_user_can_scan_valid_qr_token()
    {
        $this->actingAs($this->petugas);

        $response = $this->postJson('/api/scan', [
            'token' => 'token-budi-12345',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('warga.nama', 'Budi Warga');

        $this->assertDatabaseHas('jimpitan_masuks', [
            'warga_id' => $this->warga->id,
            'user_id'  => $this->petugas->id,
            'nominal'  => 2000,
        ]);
    }

    public function test_scan_invalid_qr_token_returns_404()
    {
        $this->actingAs($this->petugas);

        $response = $this->postJson('/api/scan', [
            'token' => 'token-invalid-99999',
        ]);

        $response->assertStatus(404);
        $response->assertJsonPath('status', 'error');
        $response->assertJsonPath('code', 'INVALID_TOKEN');
    }

    public function test_scan_inactive_warga_returns_404()
    {
        $this->actingAs($this->petugas);
        $this->warga->update(['aktif' => false]);

        $response = $this->postJson('/api/scan', [
            'token' => 'token-budi-12345',
        ]);

        $response->assertStatus(404);
        $response->assertJsonPath('status', 'error');
    }

    public function test_scan_duplicate_cooldown_returns_409()
    {
        $this->actingAs($this->petugas);

        // Scan pertama (sukses)
        $this->postJson('/api/scan', ['token' => 'token-budi-12345'])->assertStatus(201);

        // Scan kedua segera setelah pertama (warning / 409)
        $response = $this->postJson('/api/scan', ['token' => 'token-budi-12345']);

        $response->assertStatus(409);
        $response->assertJsonPath('status', 'warning');
        $response->assertJsonPath('code', 'DUPLICATE_SCAN');
    }

    // ── 4. DASHBOARD STATS TESTS ──────────────────────────────────

    public function test_dashboard_renders_stats_correctly()
    {
        $this->actingAs($this->admin);

        // Buat beberapa jimpitan masuk
        JimpitanMasuk::create([
            'warga_id' => $this->warga->id,
            'user_id'  => $this->petugas->id,
            'nominal'  => 2000,
        ]);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Admin');
        $response->assertSee('Total Warga');
        $response->assertSee('Total Petugas');
        $response->assertSee('Dana Terkumpul');
    }
}
