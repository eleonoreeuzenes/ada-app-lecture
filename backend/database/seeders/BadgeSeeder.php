<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Quantité
            ['Première Lecture', 'Terminer un premier livre', 'quantite'],
            ['Lecteur·trice assidu·e', 'Lire 5 livres', 'quantite'],
            ['Bibliophile', 'Lire 20 livres', 'quantite'],

            // Diversité
            ['Explorer les genres', 'Lire 3 genres différents', 'diversite'],
            ['Aventurier·ère littéraire', 'Lire 5 genres différents', 'diversite'],

            // Thématiques
            ['Lecture rapide', 'Lire 5 livres en moins de 2 semaines', 'temps'],
            ['Marathon de lecture', 'Terminer un livre de plus de 300 pages', 'temps'],

            // Objectifs
            ['10 livres dans un mois', 'Lire 10 livres dans le même mois', 'objectif'],
            ['30 livres dans l’année', 'Lire 30 livres dans l’année en cours', 'objectif'],
        ];

        foreach ($badges as [$name, $description, $category]) {
            Badge::firstOrCreate([
                'name' => $name,
            ], [
                'description' => $description,
                'category' => $category,
            ]);
        }
    }
}
