<?php

require 'config.php';
require 'GeminiService.php';
require 'KnowledgeService.php';

$config = include 'config.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['query'])) {
    echo json_encode(['error' => 'Missing query']);
    exit;
}

$query = $data['query'];

$knowledgeService = new KnowledgeService();
$fullContext = $knowledgeService->getFullContext();

$geminiService = new GeminiService($config);
$response = $geminiService->ask($query, $fullContext);

echo json_encode([
    'message' => $response,
    'status' => 200
]);
