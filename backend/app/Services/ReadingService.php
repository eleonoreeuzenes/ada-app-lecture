<?php

namespace App\Services;

use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Support\Facades\Auth;

class ReadingService
{
    public function addToUserLibrary(Book $book, string $status = 'to_read', int $pagesRead = 0): UserBook
    {
        $userId = Auth::id();

        // Vérifie si déjà présent
        $exists = UserBook::where('user_id', $userId)
            ->where('book_id', $book->id)
            ->exists();

        if ($exists) {
            throw new \Exception('Ce livre est déjà dans la bibliothèque de l’utilisateur.');
        }

        // Création du lien personnalisé
        return UserBook::create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'status' => $status,
            'pages_read' => $pagesRead,
        ]);
    }
}

