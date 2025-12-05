<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        $isPublished = fake()->boolean();

        return [
            'title' => $title,
            'content' => fake()->paragraphs(6, true),
            'slug' => Str::slug($title),
            // 'published' => fake()->boolean(),
            'user_id' => User::factory(),
            'created_at' => fake()->dateTimeThisYear(),
            'deleted_at' => $isPublished ? null : fake()->dateTimeThisYear(),
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => true,
            ];
        });
    }

    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => false,
            ];
        });
    }
}
