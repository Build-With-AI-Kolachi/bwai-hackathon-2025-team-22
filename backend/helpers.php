<?php

function cosineSimilarity(array $a, array $b): float
{
    $dot = 0.0;
    $normA = 0.0;
    $normB = 0.0;

    for ($i = 0; $i < count($a); $i++) {
        $dot += $a[$i] * $b[$i];
        $normA += $a[$i] ** 2;
        $normB += $b[$i] ** 2;
    }

    return $normA && $normB ? $dot / (sqrt($normA) * sqrt($normB)) : 0.0;
}
