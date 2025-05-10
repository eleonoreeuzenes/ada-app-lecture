<?php
namespace App\Services;

use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Support\Facades\Auth;

class ReadingService
{
    public function addToUserLibrary(Book $book, string $status = 'to_read', int $pagesRead = 0): UserBook
    {
        $user = Auth::user();

        if (UserBook::where('user_id', $user->id)->where('book_id', $book->id)->exists()) {
            throw new \Exception('Ce livre est déjà dans la bibliothèque de l’utilisateur.');
        }

        $userBook = UserBook::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => $status,
            'pages_read' => $pagesRead,
            // 'started_at' => now(),
            // 'finished_at' => $status === 'finished' ? now() : null,
        ]);

        if ($status === 'finished') {
            $this->assignPoints($user->id, $book->total_pages);
        }

        return $userBook;
    }

    public function assignPoints(int $userId, int $totalPages): void
    {
        $points = 0;

        if ($totalPages < 150) {
            $points = 10;
        } elseif ($totalPages <= 300) {
            $points = 20;
        } else {
            $points = 30;
        }

        $user = \App\Models\User::find($userId);
        $user->total_points += $points;
        $user->save();
    }
}

