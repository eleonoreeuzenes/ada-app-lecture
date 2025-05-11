<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\UserBook;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BadgeService
{
    public function checkAll(User $user): array
    {
        $badgesUnlocked = [];

        // Sécurité : évite les transactions multiples
        DB::beginTransaction();

        // Chaque check renvoie un tableau de badges débloqués
        $badgesUnlocked[] = $this->checkQuantiteLivres($user);
        $badgesUnlocked[] = $this->checkGenres($user);
        $badgesUnlocked[] = $this->checkTemps($user);
        $badgesUnlocked[] = $this->checkObjectifs($user);

        DB::commit();

        // Aplatir tableau et retirer les nulls
        return array_filter(array_merge(...$badgesUnlocked));
    }

    protected function checkQuantiteLivres(User $user): array
    {
        $count = UserBook::where('user_id', $user->id)
            ->where('status', 'finished')
            ->count();

        $badges = [];

        if ($count >= 1) $badges[] = $this->unlockBadge($user, 'Première Lecture');
        if ($count >= 5) $badges[] = $this->unlockBadge($user, 'Lecteur·trice assidu·e');
        if ($count >= 20) $badges[] = $this->unlockBadge($user, 'Bibliophile');

        return $badges;
    }

    protected function checkGenres(User $user): array
    {
        $genreCount = UserBook::where('user_id', $user->id)
            ->where('status', 'finished')
            ->with('book.genre')
            ->get()
            ->pluck('book.genre.name')
            ->unique()
            ->count();

        $badges = [];

        if ($genreCount >= 3) $badges[] = $this->unlockBadge($user, 'Explorer les genres');
        if ($genreCount >= 5) $badges[] = $this->unlockBadge($user, 'Aventurier·ère littéraire');

        return $badges;
    }

    protected function checkTemps(User $user): array
    {
        $finished = UserBook::where('user_id', $user->id)
            ->where('status', 'finished')
            ->get();

        $fastReads = $finished->filter(function ($book) {
            return $book->started_at && $book->finished_at &&
                   Carbon::parse($book->finished_at)->diffInDays($book->started_at) <= 14;
        });

        $longBook = $finished->filter(function ($book) {
            return $book->book->total_pages > 300;
        });

        $badges = [];

        if ($fastReads->count() >= 5) $badges[] = $this->unlockBadge($user, 'Lecture rapide');
        if ($longBook->count() >= 1) $badges[] = $this->unlockBadge($user, 'Marathon de lecture');

        return $badges;
    }

    protected function checkObjectifs(User $user): array
    {
        $now = Carbon::now();

        $monthCount = UserBook::where('user_id', $user->id)
            ->where('status', 'finished')
            ->whereBetween('finished_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->count();

        $yearCount = UserBook::where('user_id', $user->id)
            ->where('status', 'finished')
            ->whereBetween('finished_at', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])
            ->count();

        $badges = [];

        if ($monthCount >= 10) $badges[] = $this->unlockBadge($user, '10 livres dans un mois');
        if ($yearCount >= 30) $badges[] = $this->unlockBadge($user, '30 livres dans l’année');

        return $badges;
    }

    protected function unlockBadge(User $user, string $badgeName): ?string
    {
        $badge = Badge::where('name', $badgeName)->first();

        if (!$badge) return null;

        $alreadyHas = UserBadge::where('user_id', $user->id)
            ->where('badge_id', $badge->id)
            ->exists();

        if ($alreadyHas) return null;

        UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'unlocked_at' => now(),
        ]);

        return $badgeName;
    }
}
