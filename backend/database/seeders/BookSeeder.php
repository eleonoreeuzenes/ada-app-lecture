<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = Genre::all();

        $books = [
            ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'total_pages' => 96],
            ['title' => 'Les Misérables', 'author' => 'Victor Hugo', 'total_pages' => 1489],
            ['title' => 'Vingt mille lieues sous les mers', 'author' => 'Jules Verne', 'total_pages' => 422],
            ['title' => 'Le Comte de Monte-Cristo', 'author' => 'Alexandre Dumas', 'total_pages' => 1276],
            ['title' => 'Notre-Dame de Paris', 'author' => 'Victor Hugo', 'total_pages' => 544],
            ['title' => 'Les Trois Mousquetaires', 'author' => 'Alexandre Dumas', 'total_pages' => 624],
            ['title' => 'Le Rouge et le Noir', 'author' => 'Stendhal', 'total_pages' => 443],
            ['title' => 'Madame Bovary', 'author' => 'Gustave Flaubert', 'total_pages' => 432],
            ['title' => 'À la recherche du temps perdu', 'author' => 'Marcel Proust', 'total_pages' => 3031],
            ['title' => 'L\'Étranger', 'author' => 'Albert Camus', 'total_pages' => 158]
        ];

        foreach ($books as $book) {
            Book::create([
                'title' => $book['title'],
                'author' => $book['author'],
                'total_pages' => $book['total_pages'],
                'genre_id' => $genres->random()->id
            ]);
        }
    }
}
