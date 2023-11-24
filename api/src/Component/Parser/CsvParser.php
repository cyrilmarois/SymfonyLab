<?php

namespace App\Component\Parser;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;
use Symfony\Component\HttpFoundation\File\File;

class CsvParser implements ParserInterface
{
    protected array $data = [];
    private array $header = [];
    public function parse(string $fileName): array
    {
        try {
            $file = new File($fileName);
            $this->checkFileExtension($file);
            $content = $file->getContent();
            $content = explode(PHP_EOL, $content);
            // csv must be configurable , delimiter, length, ...
            foreach ($content as $i => $data) {
                if ($i === 0) {
                    $this->header = explode(',', $data);
                    continue;
                }
                $row = explode(',', $data);
                $tmpRow = [];
                foreach ($row as $i => $datum) {
                    $tmpRow[$this->header[$i]] = $datum;
                }
                $this->data[] = [...$tmpRow];
            }

            return $this->data;
        } catch (FileException $e) {
            throw $e;
        }
    }

    private function checkFileExtension(File $file): void
    {
        if ($file->getExtension() !== 'csv') {
            throw new ExtensionFileException('File extension should be csv');
        }
    }
}
