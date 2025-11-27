<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        
        return [
            'title'          => $title,
            'content'        => $this->faker->paragraphs(5, true),
            'user_id'        => User::factory(),  // otomatis bikin user baru
            'is_published'   => $this->faker->boolean(70), // 70% published
            'published_at'   => $this->faker->boolean(70) 
                                    ? $this->faker->dateTimeBetween('-1 month', 'now') 
                                    : null,
            'slug'           => Str::slug($title) . '-' . Str::random(6),
            'views'          => $this->faker->numberBetween(0, 5000),
            'featured_image' => $this->faker->boolean(50)
                                    ? $this->faker->imageUrl(800, 600, 'posts', true)
                                    : null,
            'excerpt'        => $this->faker->sentences(2, true),
            'metadata'       => [
                'category' => $this->faker->randomElement(['tech', 'life', 'news', 'tutorial']),
                'tags'     => $this->faker->words(3),
                'reading_time' => $this->faker->numberBetween(1, 10),
            ],
        ];
    }
}
