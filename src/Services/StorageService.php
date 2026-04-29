<?php

namespace App\Services;

class StorageService
{
    private $uploadDir;

    public function __construct()
    {
        $this->uploadDir = __DIR__ . '/../../assets/img/uploads/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload($file, $prefix = '')
    {
        $fileName = $prefix . time() . '_' . basename($file['name']);
        $targetPath = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'assets/img/uploads/' . $fileName;
        }
        return false;
    }

    public function delete($filePath)
    {
        $fullPath = __DIR__ . '/../../' . $filePath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}
