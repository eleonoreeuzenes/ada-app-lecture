<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserBadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $badges = $user->badges->map(function ($badge) {
            return [
            'id' => $badge->id,
            'name' => $badge->name,
            'description' => $badge->description,
            'category' => $badge->category,
            'unlocked_at' => $badge->pivot->unlocked_at,
            ];
        });

        return response()->json($badges);
    }
}

