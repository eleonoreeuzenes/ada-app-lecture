<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Science-Fiction',
            'Fantaisie',
            'Non-Fiction',
            'Romance',
            'Biographie',
            'Histoire',
            'Horreur',
            'Thriller',
            'Poésie',
            'Science'
        ];

        foreach ($genres as $genre) {
            Genre::create(['name' => $genre]);
        }
    }
}
