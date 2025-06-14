<?php

class GeminiService
{
    private $apiKey;
    private $endpoint;

    public function __construct($config)
    {
        $this->apiKey = $config['gemini_api_key'];
        $this->endpoint = $config['gemini_endpoint'];
    }

    public function ask(string $query, array $context): string
    {
        $context = json_encode($context);
        $context = str_replace('\\"', '"', $context);
        $prompt = "You are an assistant helping users based on the provided knowledge base.

Knowledge Base:
" . $context . "

User Question:
$query

Instructions:
- If you can answer the user's question using the information in the knowledge base, give a helpful answer.
- If the question is not related to anything in the knowledge base, DO NOT guess or make up an answer.
- Instead, return only a JSON response with this format only one word: Technical or Support (without quotes)
- Choose technical if the question is about system issues, development, errors, bugs, or integrations.
- Choose support for general service questions, user issues, order status, payments, etc.
";

        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ];

        $response = $this->sendCurl($this->endpoint . '?key=' . $this->apiKey, $payload);
        return $response['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from Gemini.';
    }

    private function sendCurl($url, $payload)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
}
