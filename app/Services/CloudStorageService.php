<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CloudStorageService
{
    protected $driver;
    protected $disk;

    public function __construct()
    {
        $this->driver = config('filesystems.default');
        $this->disk = Storage::disk($this->driver);
    }

    public function upload(UploadedFile $file, $path = 'uploads', $visibility = 'public')
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $path . '/' . $fileName;

        if ($this->driver === 's3') {
            return $this->uploadToS3($file, $filePath, $visibility);
        } elseif ($this->driver === 'gcs') {
            return $this->uploadToGCS($file, $filePath, $visibility);
        } elseif ($this->driver === 'spaces') {
            return $this->uploadToSpaces($file, $filePath, $visibility);
        } else {
            // Local storage
            return $this->disk->putFileAs($path, $file, $fileName, $visibility);
        }
    }

    public function getUrl($path)
    {
        if (in_array($this->driver, ['s3', 'gcs', 'spaces'])) {
            return $this->disk->url($path);
        }
        
        return Storage::url($path);
    }

    public function getTemporaryUrl($path, $expiration = 60)
    {
        if (in_array($this->driver, ['s3', 'gcs', 'spaces'])) {
            return $this->disk->temporaryUrl($path, now()->addMinutes($expiration));
        }
        
        return $this->getUrl($path);
    }

    public function delete($path)
    {
        return $this->disk->delete($path);
    }

    public function exists($path)
    {
        return $this->disk->exists($path);
    }

    private function uploadToS3($file, $path, $visibility)
    {
        // AWS S3 upload
        return $this->disk->putFileAs(dirname($path), $file, basename($path), $visibility);
    }

    private function uploadToGCS($file, $path, $visibility)
    {
        // Google Cloud Storage upload
        return $this->disk->putFileAs(dirname($path), $file, basename($path), $visibility);
    }

    private function uploadToSpaces($file, $path, $visibility)
    {
        // DigitalOcean Spaces upload
        return $this->disk->putFileAs(dirname($path), $file, basename($path), $visibility);
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;
        $this->disk = Storage::disk($driver);
        return $this;
    }
}

