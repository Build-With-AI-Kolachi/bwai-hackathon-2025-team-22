<?php

class QdrantService
{
    private $qdrantUrl;
    private $collection;
    private $apiKey;

    public function __construct($config)
    {
        $this->qdrantUrl = $config['qdrant_url'];
        $this->collection = $config['qdrant_collection'] ?? 'default_collection';
        $this->apiKey = $config['qdrant_api_key'] ?? null;
    }

    public function searchSimilarVectors(array $vector, int $limit = 5): array
    {
        $url = $this->qdrantUrl . '/collections/' . $this->collection . '/points/search';

        $payload = [
            'vector' => $vector,
            'limit' => $limit,
            'with_payload' => true
        ];

        $response = $this->sendCurl($url, $payload);
        $chunks = [];

        if (!empty($response['result'])) {
            foreach ($response['result'] as $item) {
                $chunks[] = $item['payload']['text'] ?? '';
            }
        }

        return $chunks;
    }

    private function sendCurl($url, $payload)
    {
        $headers = ['Content-Type: application/json'];

        if (!empty($this->apiKey)) {
            $headers[] = 'api-key: ' . $this->apiKey;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
