<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageService
{
    /**
     * Process and store uploaded image with thumbnail
     */
    public function processImage($uploadedFile, $directory = 'images', $thumbnailSizes = [300, 300])
    {
        try {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            
            // Store original image
            $imagePath = $uploadedFile->storeAs($directory, $filename, 'public');
            
            // Generate thumbnail
            $thumbnailPath = $this->generateThumbnail(
                $imagePath, 
                $thumbnailSizes[0], 
                $thumbnailSizes[1]
            );
            
            // Get image metadata
            $metadata = $this->getImageMetadata($uploadedFile);
            
            return [
                'original_path' => $imagePath,
                'thumbnail_path' => $thumbnailPath,
                'metadata' => $metadata,
                'success' => true
            ];
            
        } catch (\Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate thumbnail for an image
     */
    public function generateThumbnail($imagePath, $width = 300, $height = 300, $prefix = 'thumb_')
    {
        try {
            $pathInfo = pathinfo($imagePath);
            $thumbnailFilename = $prefix . $pathInfo['filename'] . '.' . $pathInfo['extension'];
            $thumbnailPath = $pathInfo['dirname'] . '/' . $thumbnailFilename;
            
            $originalFullPath = storage_path('app/public/' . $imagePath);
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            
            // Generate thumbnail using Intervention Image
            $img = Image::read($originalFullPath);
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $img->save($thumbnailFullPath);
            
            return $thumbnailPath;
            
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return $imagePath; // Return original if thumbnail generation fails
        }
    }
    
    /**
     * Generate multiple thumbnail sizes
     */
    public function generateMultipleThumbnails($imagePath, $sizes = [])
    {
        $defaultSizes = [
            'small' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 300, 'height' => 300],
            'large' => ['width' => 600, 'height' => 600],
        ];
        
        $sizes = empty($sizes) ? $defaultSizes : $sizes;
        $thumbnails = [];
        
        foreach ($sizes as $sizeName => $dimensions) {
            $prefix = $sizeName . '_';
            $thumbnails[$sizeName] = $this->generateThumbnail(
                $imagePath,
                $dimensions['width'],
                $dimensions['height'],
                $prefix
            );
        }
        
        return $thumbnails;
    }
    
    /**
     * Optimize image quality and file size
     */
    public function optimizeImage($imagePath, $quality = 85)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            $img = Image::read($fullPath);
            $img->save($fullPath, $quality);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete image and its thumbnails
     */
    public function deleteImage($imagePath, $thumbnailPath = null)
    {
        try {
            // Delete main image
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            // Delete thumbnail
            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            
            // Try to delete other possible thumbnails
            $pathInfo = pathinfo($imagePath);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['filename'];
            $extension = $pathInfo['extension'];
            
            $thumbnailPatterns = [
                'thumb_' . $filename . '.' . $extension,
                'small_' . $filename . '.' . $extension,
                'medium_' . $filename . '.' . $extension,
                'large_' . $filename . '.' . $extension,
            ];
            
            foreach ($thumbnailPatterns as $pattern) {
                $path = $directory . '/' . $pattern;
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get image metadata
     */
    private function getImageMetadata($uploadedFile)
    {
        return [
            'original_name' => $uploadedFile->getClientOriginalName(),
            'size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getMimeType(),
            'uploaded_at' => now()->toDateTimeString()
        ];
    }
    
    /**
     * Resize image to specific dimensions
     */
    public function resizeImage($imagePath, $width, $height, $maintainAspectRatio = true)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            $img = Image::read($fullPath);
            
            if ($maintainAspectRatio) {
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $img->resize($width, $height);
            }
            
            $img->save($fullPath);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Image resize failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crop image to specific dimensions
     */
    public function cropImage($imagePath, $width, $height, $x = null, $y = null)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            $img = Image::read($fullPath);
            
            if ($x !== null && $y !== null) {
                $img->crop($width, $height, $x, $y);
            } else {
                // Center crop
                $img->fit($width, $height);
            }
            
            $img->save($fullPath);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Image crop failed: ' . $e->getMessage());
            return false;
        }
    }
}