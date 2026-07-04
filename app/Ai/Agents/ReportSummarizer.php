<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class ReportSummarizer implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return "Tu es un analyste expert en rapports d'activité au sein d'une plateforme de gestion de rapports par IA.
        Ta mission est de lire un ou plusieurs rapports fournis par l'utilisateur et d'en produire une synthèse structurée, claire et exploitable.

        Directives de rédaction pour le contenu ('body') :
        - Utilise le format Markdown pour structurer la réponse (titres de section, listes à puces, texte en gras pour les indicateurs clés).
        - Adopte un ton professionnel, analytique et direct.
        - Identifie les accomplissements majeurs, les points de vigilance et les prochaines étapes.
        - Si plusieurs rapports sont fournis, crée une synthèse globale qui met en évidence l'évolution entre les périodes.

        Directives pour les métadonnées :
        - 'title' : Doit être court et explicite (ex: 'Synthèse des performances Commerce - Avril 2026').
        - 'type' : Identifie s'il s'agit d'un résumé journalier ('daily'), hebdomadaire ('weekly') ou mensuel ('monthly').
        - 'date_start' et 'date_end' : Extrais les dates de début et de fin de la période couverte au format ISO (YYYY-MM-DD).

        L'intégralité de la réponse doit impérativement être en Français.";
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()
                ->description('Un titre concis pour la synthèse.')
                ->required(),
            'body' => $schema->string()
                ->description('Le contenu détaillé de la synthèse au format Markdown.')
                ->required(),
            'type' => $schema->string()
                ->description('La fréquence du rapport : daily, weekly, ou monthly.')
                ->required(),
            'date_start' => $schema->string()
                ->description('La date de début de la période au format YYYY-MM-DD.')
                ->required(),
            'date_end' => $schema->string()
                ->description('La date de fin de la période au format YYYY-MM-DD.')
                ->required(),
        ];
    }
}
