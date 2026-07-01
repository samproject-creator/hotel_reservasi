<?php

namespace Database\Factories;

use App\Models\TipeKamar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TipeKamar>
 */
class TipeKamarFactory extends Factory
{
    protected $model = TipeKamar::class;

    // Pool nama tipe kamar beserta fasilitasnya
    private array $tipePool = [
        [
            'nama'      => 'Standard',
            'harga'     => 350000,
            'kapasitas' => 2,
            'fasilitas' => ['AC', 'TV', 'WiFi', 'Kamar Mandi Dalam'],
        ],
        [
            'nama'      => 'Deluxe',
            'harga'     => 550000,
            'kapasitas' => 2,
            'fasilitas' => ['AC', 'TV 42"', 'WiFi', 'Minibar', 'Bathtub'],
        ],
        [
            'nama'      => 'Suite',
            'harga'     => 1200000,
            'kapasitas' => 4,
            'fasilitas' => ['AC', 'TV 55"', 'WiFi', 'Minibar', 'Bathtub', 'Ruang Tamu', 'Dapur Kecil'],
        ],
        [
            'nama'      => 'Family',
            'harga'     => 750000,
            'kapasitas' => 4,
            'fasilitas' => ['AC', 'TV', 'WiFi', '2 Tempat Tidur', 'Sofa'],
        ],
        [
            'nama'      => 'Executive',
            'harga'     => 900000,
            'kapasitas' => 2,
            'fasilitas' => ['AC', 'TV 50"', 'WiFi', 'Minibar', 'Meja Kerja', 'Brankas'],
        ],
    ];

    public function definition(): array
    {
        $tipe = fake()->randomElement($this->tipePool);

        return [
            'nama_tipe'       => $tipe['nama'],
            'deskripsi'       => 'Kamar ' . $tipe['nama'] . ' dengan fasilitas lengkap dan nyaman untuk para tamu.',
            'harga_per_malam' => $tipe['harga'],
            'kapasitas'       => $tipe['kapasitas'],
            'fasilitas'       => $tipe['fasilitas'],
        ];
    }

    /** State: kamar murah (Standard) */
    public function standard(): static
    {
        return $this->state(fn() => [
            'nama_tipe'       => 'Standard',
            'harga_per_malam' => 350000,
            'kapasitas'       => 2,
            'fasilitas'       => ['AC', 'TV', 'WiFi', 'Kamar Mandi Dalam'],
        ]);
    }

    /** State: kamar premium (Suite) */
    public function suite(): static
    {
        return $this->state(fn() => [
            'nama_tipe'       => 'Suite',
            'harga_per_malam' => 1200000,
            'kapasitas'       => 4,
            'fasilitas'       => ['AC', 'TV 55"', 'WiFi', 'Minibar', 'Bathtub', 'Ruang Tamu'],
        ]);
    }
}
