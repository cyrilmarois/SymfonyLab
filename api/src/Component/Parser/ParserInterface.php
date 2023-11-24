<?php

namespace App\Component\Parser;

interface ParserInterface
{
    public function parse(string $filename): array;
}
