<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    /**
     * Upload item image.
     */
    public function uploadItemImage(UploadedFile $file): string
    {
        $filename = $this->generateFilename($file);
        $path = 'items/' . date('Y/m');

        // Store original image
        $storedPath = $file->storeAs($path, $filename, 'public');

        // Create thumbnail
        $this->createThumbnail($storedPath, $filename, $path);

        return $storedPath;
    }

    /**
     * Upload profile photo.
     */
    public function uploadProfilePhoto(UploadedFile $file): string
    {
        $filename = $this->generateFilename($file);
        $path = 'profiles/' . date('Y');

        // Store and resize profile photo
        $storedPath = $file->storeAs($path, $filename, 'public');

        // Resize profile photo to standard size
        $this->resizeImage($storedPath, 300, 300);

        return $storedPath;
    }

    /**
     * Upload payment proof.
     */
    public function uploadPaymentProof(UploadedFile $file): string
    {
        $filename = $this->generateFilename($file);
        $path = 'payments/' . date('Y/m');

        return $file->storeAs($path, $filename, 'public');
    }

    /**
     * Delete file.
     */
    public function deleteFile(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        // Also delete thumbnail if exists
        $thumbnailPath = $this->getThumbnailPath($path);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        return false;
    }

    /**
     * Generate unique filename.
     */
    private function generateFilename(UploadedFile $file): string
    {
        return time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Create thumbnail for image.
     */
    private function createThumbnail(string $path, string $filename, string $directory): void
    {
        try {
            $thumbnailPath = $directory . '/thumbnails/' . $filename;

            // Make sure thumbnail directory exists
            Storage::disk('public')->makeDirectory($directory . '/thumbnails');

            // V2: langsung Image::make()
            $image = Image::make(Storage::disk('public')->path($path));
            $image->fit(300, 300);
            $image->save(Storage::disk('public')->path($thumbnailPath));

        } catch (\Exception $e) {
            \Log::error('Thumbnail creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Resize image.
     */
    private function resizeImage(string $path, int $width, int $height): void
    {
        try {
            $image = Image::make(Storage::disk('public')->path($path));
            $image->fit($width, $height);
            $image->save(Storage::disk('public')->path($path));

        } catch (\Exception $e) {
            \Log::error('Image resize failed: ' . $e->getMessage());
        }
    }

    /**
     * Get thumbnail path from original path.
     */
    private function getThumbnailPath(string $path): string
    {
        $pathParts = explode('/', $path);
        $filename = array_pop($pathParts);
        $directory = implode('/', $pathParts);

        return $directory . '/thumbnails/' . $filename;
    }

    /**
     * Get file URL.
     */
    public function getFileUrl(string $path): string
    {
        if (empty($path)) {
            return '';
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Get thumbnail URL.
     */
    public function getThumbnailUrl(string $path): string
    {
        if (empty($path)) {
            return '';
        }

        $thumbnailPath = $this->getThumbnailPath($path);

        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::disk('public')->url($thumbnailPath);
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Clean old temporary files.
     */
    public function cleanTempFiles(int $days = 7): void
    {
        $files = Storage::disk('public')->files('temp');

        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            if ($lastModified < now()->subDays($days)->timestamp) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
