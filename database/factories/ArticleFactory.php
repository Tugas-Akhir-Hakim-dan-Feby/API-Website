<?php

namespace Database\Factories;

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
    public function definition()
    {
        $title = fake()->words(5, true);

        return [
            'user_id' => 1,
            'uuid' => Str::uuid(),
            'article_title' => $title,
            'article_slug' => Str::slug($title),
            'article_content' => fake()->paragraphs(15, true),
            'status' => mt_rand(0, 1)
        ];
    }
}
