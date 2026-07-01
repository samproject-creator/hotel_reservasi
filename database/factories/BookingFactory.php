<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Tamu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 *
 * Catatan penggunaan:
 *   Booking::factory()->create()
 *   Booking::factory()->confirmed()->create()
 *   Booking::factory()->checkin()->create()
 *   Booking::factory()->bulanLalu()->create()
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkin  = fake()->dateTimeBetween('-3 months', '+1 month');
        $checkinC = Carbon::instance($checkin);
        $malam    = fake()->numberBetween(1, 7);
        $checkout = $checkinC->copy()->addDays($malam);

        // Harga estimasi (akan di-override jika kamar di-attach lewat seeder)
        $hargaPerMalam = fake()->randomElement([350000, 550000, 750000, 1200000]);
        $total         = $hargaPerMalam * $malam;
        $dp            = fake()->randomElement([0, $total * 0.3, $total * 0.5]);

        return [
            'kode_booking'     => $this->generateKode(),
            'tamu_id'          => Tamu::factory(),
            'user_id'          => User::factory()->petugas(),
            'tanggal_checkin'  => $checkinC->format('Y-m-d'),
            'tanggal_checkout' => $checkout->format('Y-m-d'),
            'jumlah_tamu'      => fake()->numberBetween(1, 4),
            'status'           => fake()->randomElement(['pending', 'confirmed', 'checkin', 'checkout', 'cancelled']),
            'total_harga'      => $total,
            'uang_muka'        => $dp,
            'catatan'          => fake()->optional(0.3)->sentence(),
        ];
    }

    // ── States ─────────────────────────────────────────────────────────────

    public function pending(): static
    {
        return $this->state(fn() => [
            'status'          => 'pending',
            'tanggal_checkin' => now()->addDays(3)->format('Y-m-d'),
            'tanggal_checkout'=> now()->addDays(5)->format('Y-m-d'),
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn() => [
            'status'          => 'confirmed',
            'tanggal_checkin' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_checkout'=> now()->addDays(3)->format('Y-m-d'),
        ]);
    }

    public function checkin(): static
    {
        return $this->state(fn() => [
            'status'          => 'checkin',
            'tanggal_checkin' => now()->subDay()->format('Y-m-d'),
            'tanggal_checkout'=> now()->addDays(2)->format('Y-m-d'),
        ]);
    }

    public function checkout(): static
    {
        return $this->state(fn() => [
            'status'          => 'checkout',
            'tanggal_checkin' => now()->subDays(3)->format('Y-m-d'),
            'tanggal_checkout'=> now()->format('Y-m-d'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn() => ['status' => 'cancelled']);
    }

    /** State: booking bulan lalu (untuk data laporan) */
    public function bulanLalu(): static
    {
        return $this->state(function () {
            $ci = fake()->dateTimeBetween('-2 months', '-1 month');
            $ciC = Carbon::instance($ci);
            return [
                'status'          => 'checkout',
                'tanggal_checkin' => $ciC->format('Y-m-d'),
                'tanggal_checkout'=> $ciC->addDays(fake()->numberBetween(1,5))->format('Y-m-d'),
            ];
        });
    }

    // ── Helper ─────────────────────────────────────────────────────────────

    private function generateKode(): string
    {
        static $counter = 0;
        $counter++;
        return 'BK-' . now()->format('Ymd') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
    }
}
