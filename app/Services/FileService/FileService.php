<?php

namespace App\Services\FileService;

use App\Interfaces\IFileService;

abstract class FileService implements IFileService
{

    protected $file;

    public function open_file(string $file_name): void
    {
        $this->file = fopen($file_name, 'w');
    }

    public function close_file(): void
    {
        fclose($this->file);
    }
}
