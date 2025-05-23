<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'total_pages' => $this->faker->numberBetween(50, 500),
            'genre_id' => \App\Models\Genre::factory(),
        ];
    }
}
