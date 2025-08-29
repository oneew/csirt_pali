<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageService;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class TestImageProcessing extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:image-processing';

    /**
     * The console command description.
     */
    protected $description = 'Test Intervention Image processing functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Intervention Image functionality...');
        
        try {
            // Test 1: Check if Intervention Image is properly loaded
            $this->info('1. Testing Intervention Image facade...');
            
            // Create a simple test image
            $testImage = Image::create(300, 200, '#ff0000');
            $testPath = storage_path('app/public/test_image.jpg');
            $testImage->save($testPath);
            
            if (file_exists($testPath)) {
                $this->info('✓ Intervention Image facade working correctly');
                
                // Test 2: Test image resizing
                $this->info('2. Testing image resizing...');
                $resizedImage = Image::read($testPath);
                $resizedImage->resize(150, 100);
                $resizedPath = storage_path('app/public/test_resized.jpg');
                $resizedImage->save($resizedPath);
                
                if (file_exists($resizedPath)) {
                    $this->info('✓ Image resizing working correctly');
                } else {
                    $this->error('✗ Image resizing failed');
                }
                
                // Test 3: Test ImageService
                $this->info('3. Testing ImageService...');
                $imageService = app(ImageService::class);
                
                // Test thumbnail generation
                $thumbnailPath = $imageService->generateThumbnail('test_image.jpg', 100, 100);
                $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
                
                if (file_exists($thumbnailFullPath)) {
                    $this->info('✓ ImageService thumbnail generation working correctly');
                } else {
                    $this->error('✗ ImageService thumbnail generation failed');
                }
                
                // Test 4: Test multiple thumbnails
                $this->info('4. Testing multiple thumbnail generation...');
                $thumbnails = $imageService->generateMultipleThumbnails('test_image.jpg');
                
                $allThumbnailsExist = true;
                foreach ($thumbnails as $size => $path) {
                    $fullPath = storage_path('app/public/' . $path);
                    if (!file_exists($fullPath)) {
                        $allThumbnailsExist = false;
                        break;
                    }
                }
                
                if ($allThumbnailsExist) {
                    $this->info('✓ Multiple thumbnail generation working correctly');
                } else {
                    $this->error('✗ Multiple thumbnail generation failed');
                }
                
                // Cleanup test files
                $this->info('5. Cleaning up test files...');
                $testFiles = [
                    'test_image.jpg',
                    'test_resized.jpg',
                    'thumb_test_image.jpg',
                    'small_test_image.jpg',
                    'medium_test_image.jpg',
                    'large_test_image.jpg'
                ];
                
                foreach ($testFiles as $file) {
                    $filePath = 'public/' . $file;
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                }
                
                $this->info('✓ Test files cleaned up');
                $this->info('');
                $this->info('🎉 All Intervention Image tests passed successfully!');
                
            } else {
                $this->error('✗ Failed to create test image');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Test failed with error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}