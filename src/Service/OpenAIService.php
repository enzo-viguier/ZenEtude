<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAIService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function analyzeAnswers(array $answerData)
    {
        $messages = [
            ["role" => "system", "content" => "Nous allons jouer à un jeu tu joue le rôle d'un medecin automatique et je vais te donner des réponses à des questions de santé mentale. Tu dois analyser les réponses et donner un score de santé mentale entre 0 et 100. Commence ta phrase par ce score par exemple: 80 est la note mentale la plus adaptée pour ces réponses ."]
        ];
        foreach ($answerData as $data) {
            $messages[] = ["role" => "user", "content" => "{$data['question']}: {$data['answer']}"];
        }

        try {
            $response = $this->client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => "gpt-3.5-turbo",
                    'messages' => $messages,
                    'max_tokens' => 150,
                    'temperature' => 0.5
                ],
            ]);
            $content = $response->toArray();
            if (isset($content['choices'][0]['message']['content'])) {
                $textResponse = $content['choices'][0]['message']['content'];
                if (preg_match('/\d+/', $textResponse, $matches)) {
                    // Extract first number found in the response
                    $numberOutput = $matches[0];
                    return floatval($numberOutput);
                } else {
                    throw new \Exception("No numeric response found in the content.");
                }
            } else {
                throw new \Exception("Invalid response structure from OpenAI.");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error while analyzing answers: " . $e->getMessage());
        }
    }
}


