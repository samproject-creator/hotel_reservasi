<?php

namespace Database\Factories;

use App\Models\Checkin;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Checkin>
 */
class CheckinFactory extends Factory
{
    protected $model = Checkin::class;

    public function definition(): array
    {
        return [
            // booking_id harus di-set manual karena relasi one-to-one unik
            'booking_id'    => Booking::factory()->checkin(),
            'user_id'       => User::factory()->petugas(),
            'waktu_checkin' => fake()->dateTimeBetween('-30 days', 'now'),
            'no_identitas'  => fake()->optional(0.8)->numerify('##################'), // 80% isi KTP
            'catatan'       => fake()->optional(0.2)->sentence(),
        ];
    }

    /**
     * State: checkin hari ini
     */
    public function hariIni(): static
    {
        return $this->state(fn() => [
            'waktu_checkin' => now()->setHour(fake()->numberBetween(7, 14)),
        ]);
    }
}
