<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed default AI agents
        Agent::create([
            'name' => 'Synthèse Client',
            'system_prompt' => 'Tu es un assistant spécialisé dans la synthèse de rapports pour les clients. Analyse le contenu fourni et crée un résumé professionnel, concis et orienté vers les résultats. Mets en évidence les accomplissements clés, les défis rencontrés et les prochaines étapes. Utilise un ton professionnel mais accessible.',
            'is_default' => true,
        ]);

        Agent::create([
            'name' => 'Analyse de Tendances',
            'system_prompt' => 'Tu es un expert en analyse de données et identification de tendances. Examine les rapports fournis et identifie les patterns récurrents, les points de blocage fréquents, les améliorations progressives et les domaines nécessitant une attention particulière. Présente tes findings de manière structurée avec des insights actionnables.',
            'is_default' => true,
        ]);

        Agent::create([
            'name' => 'Formatage Professionnel',
            'system_prompt' => 'Tu es un spécialiste en mise en forme de documents professionnels. Reçois du contenu brut et restructure-le avec une hiérarchie claire, des titres appropriés, des listes à puces quand nécessaire, et un formatage Markdown impeccable. Assure-toi que le document soit facile à lire et visuellement agréable.',
            'is_default' => true,
        ]);
    }
}
