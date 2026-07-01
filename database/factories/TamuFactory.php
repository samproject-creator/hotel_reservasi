<?php

namespace Database\Factories;

use App\Models\Tamu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tamu>
 */
class TamuFactory extends Factory
{
    protected $model = Tamu::class;

    public function definition(): array
    {
        $gender = fake()->randomElement(['L', 'P']);

        return [
            'nama_lengkap'    => $gender === 'L'
                ? fake('id_ID')->firstNameMale() . ' ' . fake('id_ID')->lastName()
                : fake('id_ID')->firstNameFemale() . ' ' . fake('id_ID')->lastName(),
            'nik'             => $this->generateNik(),
            'email'           => fake()->optional(0.7)->safeEmail(), // 70% punya email
            'no_hp'           => '08' . fake()->numerify('#########'),
            'alamat'          => fake('id_ID')->address(),
            'jenis_kelamin'   => $gender,
            'tanggal_lahir'   => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'pekerjaan'       => fake()->randomElement([
                'Wiraswasta', 'PNS', 'Karyawan Swasta', 'Dokter',
                'Guru', 'Pengacara', 'Mahasiswa', 'Pedagang',
                'TNI/Polri', 'Ibu Rumah Tangga',
            ]),
            'kewarganegaraan' => fake()->randomElement([
                'Indonesia', 'Indonesia', 'Indonesia', // bobot WNI lebih tinggi
                'Malaysia', 'Singapura', 'Belanda', 'Australia',
            ]),
        ];
    }

    /** State: tamu asing (WNA) */
    public function asing(): static
    {
        return $this->state(fn() => [
            'kewarganegaraan' => fake()->randomElement(['Malaysia', 'Singapura', 'Belanda', 'Jepang', 'Korea Selatan']),
        ]);
    }

    /** State: tamu dalam negeri (WNI) */
    public function lokal(): static
    {
        return $this->state(fn() => ['kewarganegaraan' => 'Indonesia']);
    }

    /**
     * Generate NIK 16 digit yang unik dan realistis.
     * Format: [kode wilayah 6 digit][tanggal lahir 6 digit][urut 4 digit]
     */
    private function generateNik(): string
    {
        $kodeWilayah = fake()->numerify('######');
        $tgl = fake()->dateTimeBetween('-60 years', '-18 years')->format('dmY');
        // Untuk perempuan, tanggal ditambah 40 (standar NIK Indonesia)
        $urut = fake()->numerify('####');
        return $kodeWilayah . substr($tgl, 0, 6) . $urut;
    }
}
