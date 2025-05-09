<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreMethodCreatesBook()
    {
        $genre = Genre::factory()->create();

        $bookData = [
            'title' => 'Le Petit Prince',
            'author' => 'Antoine de Saint-Exupéry',
            'total_pages' => 96,
            'genre_id' => $genre->id,
        ];

        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/books', $bookData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Livre créé avec succès.',
                'book' => [
                    'title' => 'Le Petit Prince',
                    'author' => 'Antoine de Saint-Exupéry',
                    'total_pages' => 96,
                    'genre_id' => $genre->id,
                ],
            ]);

        $this->assertDatabaseHas('books', $bookData);
    }

    public function testStoreMethodPreventsDuplicateBook()
    {
        $genre = Genre::factory()->create();

        $existingBook = Book::factory()->create([
            'title' => 'Le Petit Prince',
            'author' => 'Antoine de Saint-Exupéry',
            'total_pages' => 96,
            'genre_id' => $genre->id,
        ]);

        $bookData = [
            'title' => 'Le Petit Prince',
            'author' => 'Antoine de Saint-Exupéry',
            'total_pages' => 96,
            'genre_id' => $genre->id,
        ];

        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/books', $bookData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Livre créé avec succès.',
                'book' => [
                    'title' => 'Le Petit Prince',
                    'author' => 'Antoine de Saint-Exupéry',
                    'total_pages' => 96,
                    'genre_id' => $genre->id,
                ],
            ]);


        $this->assertDatabaseCount('books', 1);
    }
}
