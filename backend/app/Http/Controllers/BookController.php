<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBook;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;


class BookController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'total_pages' => 'required|integer|min:1',
            'genre_id' => 'required|exists:genres,id',
        ]);

        //TODO: search if possible to handle better duplicates
        $book = Book::firstOrCreate($validated);

        return response()->json([
            'message' => 'Livre créé avec succès.',
            'book' => $book,
        ], 201);
    }

public function search(Request $request)
{
    $query = $request->input('title');

    $books = Book::where('title', 'ILIKE', "%{$query}%")
        ->with('genre')
        ->limit(10)
        ->get();

    return response()->json($books);
}


}
