<?php

class KnowledgeService
{
    private $chunks;

    public function __construct($filePath = 'knowledge.json')
    {
        $this->chunks = json_decode(file_get_contents($filePath), true);
    }

    public function getFullContext(): array
    {
        return $this->chunks;
    }
}
