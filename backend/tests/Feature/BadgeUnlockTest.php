<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeUnlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_reading_unlocks_first_badge()
    {
        $genre = Genre::factory()->create();
        $badge = Badge::create([
            'name' => 'Première Lecture',
            'description' => 'Terminer un premier livre',
            'category' => 'quantite',
        ]);

        $user = User::factory()->createOne();
        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Author',
            'total_pages' => 100,
            'genre_id' => $genre->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/user-books', [
            'book_id' => $book->id,
            'status' => 'finished',
            'pages_read' => 100,
        ]);

        $response->assertStatus(201);


        $user->refresh();

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
    }
}
