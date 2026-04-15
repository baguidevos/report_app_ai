<?php

namespace App\Services;

use App\Models\Agent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $model;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        $this->model = config('services.openai.model', env('OPENAI_MODEL', 'gpt-3.5-turbo'));
        $this->baseUrl = 'https://api.openai.com/v1';
    }

    /**
     * Execute a prompt with the given content.
     *
     * @param string $systemPrompt The system prompt (agent's role)
     * @param string $content The content to process
     * @param array $options Additional options
     * @return string The AI response
     */
    public function executePrompt(string $systemPrompt, string $content, array $options = []): string
    {
        if (!$this->apiKey) {
            Log::warning('OpenAI API key not configured');
            return "Erreur: Clé API non configurée. Veuillez ajouter OPENAI_API_KEY dans votre fichier .env";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $content,
                    ],
                ],
                'temperature' => $options['temperature'] ?? 0.7,
                'max_tokens' => $options['max_tokens'] ?? 2000,
            ]);

            if ($response->failed()) {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                
                return "Erreur API: " . ($response->json()['error']['message'] ?? 'Erreur inconnue');
            }

            $data = $response->json();
            
            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('Invalid OpenAI response structure', ['data' => $data]);
                return "Erreur: Réponse API invalide";
            }

            return $data['choices'][0]['message']['content'];

        } catch (\Exception $e) {
            Log::error('OpenAI API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return "Erreur lors de l'appel API: " . $e->getMessage();
        }
    }

    /**
     * Execute an agent on content.
     *
     * @param Agent $agent The agent to execute
     * @param string $content The content to process
     * @return string The processed content
     */
    public function executeAgent(Agent $agent, string $content): string
    {
        return $this->executePrompt($agent->system_prompt, $content);
    }
}
