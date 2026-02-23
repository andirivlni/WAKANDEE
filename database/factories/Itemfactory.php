<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['buku', 'seragam', 'alat_praktikum', 'lainnya']),
            'type' => $this->faker->randomElement(['gift', 'sale']),
            'price' => $this->faker->optional()->numberBetween(10000, 200000),
            'condition' => $this->faker->randomElement(['baru', 'sangat_baik', 'baik', 'cukup']),
            'images' => json_encode([
                'items/dummy/item-' . $this->faker->numberBetween(1, 50) . '-1.jpg',
                'items/dummy/item-' . $this->faker->numberBetween(1, 50) . '-2.jpg',
            ]),
            'legacy_message' => $this->faker->randomElement([
                'Semoga bermanfaat untuk adik kelas!',
                'Belajar yang rajin ya, sukses selalu!',
                'Warisan ilmu untuk generasi berikutnya',
                'Pakai dengan baik, jangan lupa belajar',
                'Dari kakak untuk adik, semoga berkah',
                'Jangan menyerah dalam belajar!',
                'Teruslah berprestasi!',
                'Semoga membantu perjalanan akademikmu',
                'Berbagi itu indah',
                'Lakukan yang terbaik!'
            ]),
            'status' => 'pending',
            'rejection_reason' => null,
            'approved_by' => null,
            'approved_at' => null,
            'views_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the item is a gift.
     */
    public function gift(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'gift',
            'price' => null,
        ]);
    }

    /**
     * Indicate that the item is for sale.
     */
    public function sale(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'sale',
            'price' => $this->faker->numberBetween(10000, 200000),
        ]);
    }

    /**
     * Indicate that the item is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => 1,
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the item is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that the item is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'rejection_reason' => 'Foto kurang jelas. Silakan upload ulang dengan foto yang lebih baik.',
            'approved_by' => 1,
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the item is sold.
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
            'approved_by' => 1,
            'approved_at' => now()->subDays(rand(1, 10)),
        ]);
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Item $item) {
            //
        })->afterCreating(function (Item $item) {
            //
        });
    }
}
