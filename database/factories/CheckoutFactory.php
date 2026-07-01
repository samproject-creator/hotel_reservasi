<?php

namespace Database\Factories;

use App\Models\Checkout;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Checkout>
 */
class CheckoutFactory extends Factory
{
    protected $model = Checkout::class;

    public function definition(): array
    {
        $biayaTambahan = fake()->optional(0.3)->randomFloat(0, 50000, 500000) ?? 0;
        $sisaTagihan   = fake()->numberBetween(200000, 2000000);
        $totalTagihan  = $sisaTagihan + $biayaTambahan;

        $metodePembayaran = fake()->randomElement(['cash', 'cash', 'transfer', 'kartu_kredit', 'debit']);
        // cash biasanya bayar pas-pasan atau lebih
        $totalBayar = $metodePembayaran === 'cash'
            ? $this->bulatkanRupiah($totalTagihan + fake()->numberBetween(0, 50000))
            : $totalTagihan;
        $kembalian  = max(0, $totalBayar - $totalTagihan);

        return [
            'booking_id'         => Booking::factory()->checkout(),
            'user_id'            => User::factory()->petugas(),
            'waktu_checkout'     => fake()->dateTimeBetween('-30 days', 'now'),
            'total_tagihan'      => $totalTagihan,
            'biaya_tambahan'     => $biayaTambahan,
            'keterangan_biaya'   => $biayaTambahan > 0 ? fake()->randomElement([
                'Biaya laundry', 'Biaya minibar', 'Biaya kerusakan fasilitas',
                'Biaya perpanjangan menginap', 'Biaya room service',
            ]) : null,
            'metode_pembayaran'  => $metodePembayaran,
            'total_bayar'        => $totalBayar,
            'kembalian'          => $kembalian,
            'catatan'            => fake()->optional(0.15)->sentence(),
        ];
    }

    /** State: bayar cash */
    public function cash(): static
    {
        return $this->state(fn() => ['metode_pembayaran' => 'cash']);
    }

    /** State: bayar transfer */
    public function transfer(): static
    {
        return $this->state(fn() => [
            'metode_pembayaran' => 'transfer',
            'kembalian'         => 0,
        ]);
    }

    /** State: checkout hari ini */
    public function hariIni(): static
    {
        return $this->state(fn() => [
            'waktu_checkout' => now()->setHour(fake()->numberBetween(10, 15)),
        ]);
    }

    private function bulatkanRupiah(float $nominal): float
    {
        // Bulatkan ke ribuan terdekat
        return ceil($nominal / 1000) * 1000;
    }
}
