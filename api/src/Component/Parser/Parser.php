<?php

namespace App\Component\Parser;

class Parser
{
    public function __construct(
        private ParserInterface $parser,
        private string $fileName,
    ) {
    }

    public function parse()
    {
        try {
            return $this->parser->parse($this->fileName);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
