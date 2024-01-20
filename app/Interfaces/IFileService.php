<?php

namespace App\Interfaces;
interface  IFileService
{

    // bool | resource
    public function open_file(string $file_name);

    public function close_file();
}
