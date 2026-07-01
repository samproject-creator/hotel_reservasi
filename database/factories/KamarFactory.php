<?php

namespace Database\Factories;

use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kamar>
 */
class KamarFactory extends Factory
{
    protected $model = Kamar::class;

    public function definition(): array
    {
        $lantai = fake()->numberBetween(1, 5);
        $nomorUrut = fake()->unique()->numberBetween(1, 20);

        return [
            'nomor_kamar'   => $lantai . str_pad($nomorUrut, 2, '0', STR_PAD_LEFT), // e.g. 101, 215
            'tipe_kamar_id' => TipeKamar::factory(),
            'lantai'        => $lantai,
            'status'        => fake()->randomElement(['tersedia', 'tersedia', 'tersedia', 'maintenance']),
            // bobot tersedia lebih tinggi
            'keterangan'    => null,
        ];
    }

    /** State: kamar tersedia */
    public function tersedia(): static
    {
        return $this->state(fn() => ['status' => 'tersedia']);
    }

    /** State: kamar sedang ditempati */
    public function ditempati(): static
    {
        return $this->state(fn() => ['status' => 'ditempati']);
    }

    /** State: kamar dalam maintenance */
    public function maintenance(): static
    {
        return $this->state(fn() => [
            'status'     => 'maintenance',
            'keterangan' => 'Sedang dalam proses perbaikan fasilitas.',
        ]);
    }

    /** State: kamar di lantai tertentu */
    public function lantai(int $lantai): static
    {
        return $this->state(fn() => ['lantai' => $lantai]);
    }
}
