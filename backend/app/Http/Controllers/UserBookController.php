<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Support\Facades\Auth;
use App\Services\ReadingService;

class UserBookController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'book_id' => 'required|exists:books,id',
        'status' => 'required|in:to_read,in_progress,finished',
        'pages_read' => 'required|integer|min:0',
    ]);

    $exists = UserBook::where('user_id', Auth::id())
        ->where('book_id', $validated['book_id'])
        ->exists();

    if ($exists) {
        return response()->json([
            'message' => 'Ce livre est déjà dans votre bibliothèque.'
        ], 409);
    }

    $userBook = UserBook::create([
        'user_id' => Auth::id(),
        'book_id' => $validated['book_id'],
        'status' => $validated['status'],
        'pages_read' => $validated['pages_read'],
    ]);

    return response()->json([
        'message' => 'Lecture ajoutée ',
        'user_book' => $userBook,
    ], 201);
}

public function storeFull(Request $request, ReadingService $readingService)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'total_pages' => 'required|integer|min:1',
        'genre_id' => 'required|exists:genres,id',
        'status' => 'required|in:to_read,in_progress,finished',
        'pages_read' => 'required|integer|min:0',
    ]);

    $book = Book::firstOrCreate(
        [
            'title' => $validated['title'],
            'author' => $validated['author'],
            'total_pages' => $validated['total_pages'],
        ],
        [
            'genre_id' => $validated['genre_id'],
        ]
    );

    try {
        $userBook = $readingService->addToUserLibrary(
            $book,
            $validated['status'],
            $validated['pages_read']
        );
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 409);
    }

    return response()->json([
        'message' => 'Livre et lecture ajoutés avec succès ✅',
        'book' => $book,
        'user_book' => $userBook,
    ], 201);
}
}
