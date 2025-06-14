<?php

class EmbeddingService
{
    private $apiKey;

    public function __construct($config)
    {
        $this->apiKey = $config['gemini_api_key'];
    }

    public function getEmbedding(string $text): array
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/embedding-001:embedText?key=' . $this->apiKey;
        $payload = ['text' => $text];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        return $data['embedding']['values'] ?? [];
    }
}
