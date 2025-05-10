<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Genre;
use App\Models\UserBook;
use App\Models\Badge;
use App\Models\UserBadge;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BookladSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'booklad@dev.com',
        ], [
            'username' => 'booklad',
            'password' => Hash::make('Password:123'),
            'total_points' => 0,
        ]);

        $books = Book::take(5)->with('genre')->get();
        $pointsTotal = 0;

        foreach ($books as $index => $book) {
            $status = 'finished';
            $pagesRead = $book->total_pages;
            $startedAt = Carbon::now()->subDays(5 + $index * 3);
            $finishedAt = Carbon::now()->subDays($index * 2);

            UserBook::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'status' => $status,
                'pages_read' => $pagesRead,
                'started_at' => $startedAt,
                'finished_at' => $finishedAt,
            ]);

            $pages = $book->total_pages;
            if ($pages < 150) $pointsTotal += 10;
            elseif ($pages <= 300) $pointsTotal += 20;
            else $pointsTotal += 30;
        }

        $user->total_points = $pointsTotal;
        $user->save();

        $badgeNames = [
            'Première Lecture',
            'Lecteur·trice assidu·e',
            'Explorer les genres'
        ];

        foreach ($badgeNames as $badgeName) {
            $badge = Badge::where('name', $badgeName)->first();
            if ($badge) {
                UserBadge::firstOrCreate([
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                ], [
                    'unlocked_at' => now(),
                ]);
            }
        }
    }
}
