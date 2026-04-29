<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LLMService
{
    private $client;
    private $apiKey;
    private $model;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.openai.com/v1/']);
        $this->apiKey = $_ENV['LLM_API_KEY'];
        $this->model  = $_ENV['LLM_MODEL'] ?? 'gpt-4-turbo-preview';
    }

    public function getResponse($message, $history = [])
    {
        try {
            $messages = [
                ['role' => 'system', 'content' => "You are Praveen's Portfolio AI assistant. You help visitors learn about Praveen's work as a Senior UX/UI Architect. You are professional, creative, and concise. Praveen has 18+ years of experience and is an expert in Agentic Design."]
            ];

            foreach ($history as $h) {
                $messages[] = ['role' => $h['role'], 'content' => $h['content']];
            }

            $messages[] = ['role' => 'user', 'content' => $message];

            $response = $this->client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'];
        } catch (GuzzleException $e) {
            return "I'm having trouble connecting to my brain right now. Please try again later. (Error: " . $e->getMessage() . ")";
        }
    }

    public function generateImage($prompt, $size = '1024x1024')
    {
        try {
            $response = $this->client->post('images/generations', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'dall-e-3',
                    'prompt' => $prompt,
                    'n' => 1,
                    'size' => $size,
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['data'][0]['url'];
        } catch (GuzzleException $e) {
            return false;
        }
    }
}
