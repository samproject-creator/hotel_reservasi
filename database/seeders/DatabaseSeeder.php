<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Models\Booking;
use App\Models\Checkin;
use App\Models\Checkout;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // 1. Users
        $admin = User::create([
            'name' => 'Super Admin', 'email' => 'admin@hotel.com',
            'password' => Hash::make('password'), 'role' => 'admin',
            'no_hp' => '081234567890', 'is_active' => true,
        ]);
        $petugas = User::create([
            'name' => 'Petugas Front Desk', 'email' => 'petugas@hotel.com',
            'password' => Hash::make('password'), 'role' => 'petugas',
            'no_hp' => '081234567891', 'is_active' => true,
        ]);
        User::factory()->petugas()->count(3)->create();

        // 2. Tipe Kamar
        $standard = TipeKamar::create(['nama_tipe' => 'Standard', 'deskripsi' => 'Kamar standar nyaman.', 'harga_per_malam' => 350000, 'kapasitas' => 2, 'fasilitas' => ['AC','TV','WiFi','Kamar Mandi']]);
        $deluxe   = TipeKamar::create(['nama_tipe' => 'Deluxe',   'deskripsi' => 'Kamar deluxe premium.', 'harga_per_malam' => 550000, 'kapasitas' => 2, 'fasilitas' => ['AC','TV 42"','WiFi','Minibar','Bathtub']]);
        $suite    = TipeKamar::create(['nama_tipe' => 'Suite',    'deskripsi' => 'Kamar suite mewah.',    'harga_per_malam' => 1200000,'kapasitas' => 4, 'fasilitas' => ['AC','TV 55"','WiFi','Minibar','Bathtub','Ruang Tamu']]);
        $family   = TipeKamar::create(['nama_tipe' => 'Family',   'deskripsi' => 'Kamar keluarga luas.',  'harga_per_malam' => 750000, 'kapasitas' => 4, 'fasilitas' => ['AC','TV','WiFi','2 Tempat Tidur','Sofa']]);

        // 3. Kamar
        $kamarDef = [
            ['101', $standard->id, 1], ['102', $standard->id, 1], ['103', $standard->id, 1], ['104', $standard->id, 1],
            ['201', $deluxe->id,   2], ['202', $deluxe->id,   2], ['203', $deluxe->id,   2], ['204', $deluxe->id,   2],
            ['301', $suite->id,    3], ['302', $suite->id,    3], ['303', $family->id,   3], ['304', $family->id,   3],
        ];

        $kamar = [];
        foreach ($kamarDef as [$nomor, $tipeId, $lantai]) {
            $kamar[$nomor] = Kamar::create([
                'nomor_kamar'   => $nomor, 
                'tipe_kamar_id' => $tipeId, 
                'lantai'        => $lantai, 
                'status'        => 'tersedia',
                'keterangan'    => 'Kamar istirahat.',
                'images'        => [] // <-- Berikan array kosong sebagai default nilai awal seeder
            ]);
        }

        // 4. Tamu
        $tamuData = [
            ['Budi Santoso','3273010101900001','budi@email.com','08123456001','L','Wiraswasta'],
            ['Siti Rahayu','3273020202920002','siti@email.com','08123456002','P','Karyawan Swasta'],
            ['Ahmad Fauzi','3273030303880003',null,'08123456003','L','PNS'],
            ['Dewi Lestari','3273040404950004','dewi@email.com','08123456004','P','Dokter'],
            ['Rendi Pratama','3273050505870005','rendi@email.com','08123456005','L','Pedagang'],
        ];
        $tamus = [];
        foreach ($tamuData as [$nama, $nik, $email, $hp, $jk, $pekerjaan]) {
            $tamus[] = Tamu::create(['nama_lengkap' => $nama, 'nik' => $nik, 'email' => $email, 'no_hp' => $hp, 'jenis_kelamin' => $jk, 'alamat' => 'Jl. Contoh Bandung', 'tanggal_lahir' => '1990-01-01', 'pekerjaan' => $pekerjaan, 'kewarganegaraan' => 'Indonesia']);
        }
        Tamu::factory()->count(10)->create();

        // 5. Booking realistis
        $skenario = [
            [$tamus[0], '101', $standard, 'checkout', true,  true,  -10, -7],
            [$tamus[1], '201', $deluxe,   'checkin',  true,  false, -1,   2],
            [$tamus[2], '301', $suite,    'confirmed', false, false,  2,   5],
            [$tamus[3], '303', $family,   'pending',  false, false,  7,   9],
            [$tamus[4], '102', $standard, 'cancelled', false, false, -5,  -3],
        ];

        static $kodeCounter = 0;
        foreach ($skenario as [$tamu, $kamarNomor, $tipe, $status, $ci, $co, $addCi, $addCo]) {
            $kodeCounter++;
            $ciDate  = now()->addDays($addCi)->format('Y-m-d');
            $coDate  = now()->addDays($addCo)->format('Y-m-d');
            $malam   = Carbon::parse($ciDate)->diffInDays($coDate);
            $total   = $tipe->harga_per_malam * $malam;
            $dp      = $status !== 'cancelled' ? round($total * 0.3) : 0;
            $kamar   = $kamar[$kamarNomor];

            $booking = Booking::create([
                'kode_booking' => 'BK-'.now()->format('Ymd').'-'.str_pad($kodeCounter, 4, '0', STR_PAD_LEFT),
                'tamu_id' => $tamu->id, 'user_id' => $petugas->id,
                'tanggal_checkin' => $ciDate, 'tanggal_checkout' => $coDate,
                'jumlah_tamu' => 2, 'status' => $status,
                'total_harga' => $total, 'uang_muka' => $dp,
            ]);
            $booking->kamar()->attach($kamar->id, ['harga_malam' => $tipe->harga_per_malam, 'jumlah_malam' => $malam, 'subtotal' => $total]);
            if ($status === 'checkin') $kamar->update(['status' => 'ditempati']);
            if ($ci) Checkin::create(['booking_id' => $booking->id, 'user_id' => $petugas->id, 'waktu_checkin' => Carbon::parse($ciDate)->setHour(14), 'no_identitas' => $tamu->nik]);
            if ($co) {
                $sisa = $total - $dp;
                Checkout::create(['booking_id' => $booking->id, 'user_id' => $petugas->id, 'waktu_checkout' => Carbon::parse($coDate)->setHour(12), 'total_tagihan' => $sisa, 'biaya_tambahan' => 0, 'metode_pembayaran' => 'cash', 'total_bayar' => $sisa, 'kembalian' => 0]);
            }
        }

        // Booking historis 3 bulan terakhir untuk grafik
        for ($i = 1; $i <= 10; $i++) {
            $kodeCounter++;
            $ciDate = Carbon::now()->subMonths(rand(1,3))->subDays(rand(1,20))->format('Y-m-d');
            $coDate = Carbon::parse($ciDate)->addDays(rand(1,5))->format('Y-m-d');
            $malam  = Carbon::parse($ciDate)->diffInDays($coDate);
            $tamu   = collect($tamus)->random();
            $kamar  = $kamar[collect(['102','103','202','203','302','304'])->random()];
            $tipe   = $kamar->tipeKamar;
            $total  = $tipe->harga_per_malam * $malam;
            $sisa   = $total * 0.7;

            $booking = Booking::create([
                'kode_booking' => 'BK-'.now()->format('Ymd').'-'.str_pad($kodeCounter, 4, '0', STR_PAD_LEFT),
                'tamu_id' => $tamu->id, 'user_id' => $petugas->id,
                'tanggal_checkin' => $ciDate, 'tanggal_checkout' => $coDate,
                'jumlah_tamu' => 2, 'status' => 'checkout',
                'total_harga' => $total, 'uang_muka' => $total * 0.3,
            ]);
            $booking->kamar()->attach($kamar->id, ['harga_malam' => $tipe->harga_per_malam, 'jumlah_malam' => $malam, 'subtotal' => $total]);
            Checkin::create(['booking_id' => $booking->id, 'user_id' => $petugas->id, 'waktu_checkin' => Carbon::parse($ciDate)->setHour(14), 'no_identitas' => $tamu->nik]);
            Checkout::create(['booking_id' => $booking->id, 'user_id' => $petugas->id, 'waktu_checkout' => Carbon::parse($coDate)->setHour(12), 'total_tagihan' => $sisa, 'biaya_tambahan' => 0, 'metode_pembayaran' => 'cash', 'total_bayar' => $sisa, 'kembalian' => 0]);
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('Seeder selesai! Admin: admin@hotel.com / password | Petugas: petugas@hotel.com / password');
    }
}
