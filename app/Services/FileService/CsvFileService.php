<?php

namespace App\Services\FileService;

class CsvFileService extends FileService
{
    public function generate_file_content(array $fields, string $separator = ',')
    {
        fputcsv($this->file, $fields, $separator);
    }
}
