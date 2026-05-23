<?php

namespace Database\Seeders;

use App\Models\JimpitanKeluar;
use App\Models\JimpitanMasuk;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat akun Admin ────────────────────────────────────
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@jimput.test',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // ── 2. Buat akun Petugas ──────────────────────────────────
        $petugas = [];
        $dataPetugas = [
            ['name' => 'Pak Budi Santoso',   'username' => 'budi'],
            ['name' => 'Pak Cahyo Wibowo',   'username' => 'cahyo'],
            ['name' => 'Pak Darmawan',       'username' => 'darmawan'],
        ];

        foreach ($dataPetugas as $p) {
            $petugas[] = User::firstOrCreate(
                ['username' => $p['username']],
                [
                    'name'     => $p['name'],
                    'email'    => $p['username'] . '@jimput.test',
                    'username' => $p['username'],
                    'password' => Hash::make('petugas123'),
                    'role'     => 'petugas',
                ]
            );
        }

        // ── 3. Buat data Warga (20 warga) ─────────────────────────
        $namaWarga = [
            'Pak Ahmad Supriyadi',   'Ibu Siti Rahayu',
            'Pak Bejo Utomo',        'Ibu Dewi Lestari',
            'Pak Hendra Kurniawan',  'Ibu Fitri Handayani',
            'Pak Gunawan Setiawan',  'Ibu Rina Susanti',
            'Pak Iwan Prasetyo',     'Ibu Juwita Wulandari',
            'Pak Krisna Adi',        'Ibu Lina Marlina',
            'Pak Maman Abdurrahman', 'Ibu Novi Anggraini',
            'Pak Oji Hermawan',      'Ibu Puji Astuti',
            'Pak Rudi Hartono',      'Ibu Sri Wahyuni',
            'Pak Teguh Basuki',      'Ibu Umi Kalsum',
        ];

        $wargas = [];
        foreach ($namaWarga as $i => $nama) {
            $noRumah = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            $wargas[] = Warga::firstOrCreate(
                ['nama_warga' => $nama],
                [
                    'qr_token'   => 'token-warga-' . Str::random(12) . '-' . ($i + 1000),
                    'nama_warga' => $nama,
                    'no_rumah'   => $noRumah,
                    'rt_rw'      => 'RT 0' . (($i % 3) + 1) . '/RW 02',
                    'aktif'      => true,
                ]
            );
        }

        // ── 4. Buat data Jimpitan (historis 14 hari terakhir) ─────
        // Hanya buat jika belum ada data
        if (JimpitanMasuk::count() === 0) {
            for ($hari = 13; $hari >= 0; $hari--) {
                $tgl = now()->subDays($hari);
                // Random 5-15 warga per hari
                $sampel = collect($wargas)->random(rand(5, min(15, count($wargas))));

                foreach ($sampel as $warga) {
                    $petugasRandom = $petugas[array_rand($petugas)];
                    JimpitanMasuk::create([
                        'warga_id'   => $warga->id,
                        'user_id'    => $petugasRandom->id,
                        'nominal'    => 2000,
                        'created_at' => $tgl->copy()->setTime(rand(19, 22), rand(0, 59)),
                        'updated_at' => $tgl->copy()->setTime(rand(19, 22), rand(0, 59)),
                    ]);
                }
            }
        }

        // ── 5. Buat data Pengeluaran (historis 14 hari terakhir) ──
        if (JimpitanKeluar::count() === 0) {
            $daftarKeterangan = [
                'Membayar iuran kebersihan & angkut sampah bulanan',
                'Pembelian bohlam lampu jalan RT yang mati',
                'Pemberian santunan sosial bagi warga yang sakit',
                'Konsumsi rapat kas RT bulanan',
                'Perbaikan pintu pos kamling',
                'Kas sosial pembelian karangan bunga duka warga',
            ];

            // Buat 6 data pengeluaran dalam 14 hari terakhir
            for ($i = 0; $i < 6; $i++) {
                $tgl = now()->subDays(rand(1, 13));
                $petugasRandom = $petugas[array_rand($petugas)];
                JimpitanKeluar::create([
                    'user_id' => $petugasRandom->id,
                    'nominal' => rand(2, 10) * 10000, // Rp 20.000 - Rp 100.000
                    'keterangan' => $daftarKeterangan[$i],
                    'tanggal' => $tgl->toDateString(),
                    'created_at' => $tgl->copy()->setTime(10, rand(0, 59)),
                    'updated_at' => $tgl->copy()->setTime(10, rand(0, 59)),
                ]);
            }
        }

        $this->command->info('✅ Seeder selesai!');
        $this->command->table(
            ['Role', 'Username', 'Password'],
            [
                ['Admin',   'admin',    'admin123'],
                ['Petugas', 'budi',     'petugas123'],
                ['Petugas', 'cahyo',    'petugas123'],
                ['Petugas', 'darmawan', 'petugas123'],
            ]
        );
    }
}
