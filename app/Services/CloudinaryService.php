<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Uploads an image file to Cloudinary and returns its secure URL.
     * If CLOUDINARY_URL is not set or fails, falls back gracefully to local public storage.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public static function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $cloudinaryUrl = config('services.cloudinary.url') ?? env('CLOUDINARY_URL');

        if (!empty($cloudinaryUrl)) {
            try {
                $cloudinary = new Cloudinary($cloudinaryUrl);
                $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => $folder,
                ]);

                if (isset($result['secure_url'])) {
                    return $result['secure_url'];
                }
            } catch (\Throwable $e) {
                Log::error('Cloudinary upload error: ' . $e->getMessage());
            }
        }

        // Fallback to local public disk if Cloudinary is not configured or fails
        return $file->store($folder, 'public');
    }
}
