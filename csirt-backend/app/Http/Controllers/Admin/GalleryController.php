<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\ActivityLog;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;

class GalleryController extends Controller
{
    protected $imageService;
    
    public function __construct(ImageService $imageService)
    {
        $this->middleware(['auth', 'role:admin,operator']);
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of gallery items
     */
    public function index(Request $request)
    {
        $query = Gallery::with('uploader');

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('uploader')) {
            $query->where('uploaded_by', $request->uploader);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'order');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $galleries = $query->paginate(20);
        $uploaders = \App\Models\User::active()->orderBy('first_name')->get();

        return view('admin.gallery.index', compact('galleries', 'uploaders'));
    }

    /**
     * Show the form for creating a new gallery item
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created gallery item
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle image upload using ImageService
            $imagePath = null;
            $thumbnailPath = null;

            if ($request->hasFile('image')) {
                $result = $this->imageService->processImage(
                    $request->file('image'),
                    'gallery',
                    [300, 300]
                );
                
                if ($result['success']) {
                    $imagePath = $result['original_path'];
                    $thumbnailPath = $result['thumbnail_path'];
                    $metadata = $result['metadata'];
                } else {
                    return back()->with('error', 'Image processing failed: ' . $result['error']);
                }
            }

            $gallery = Gallery::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'image_path' => $imagePath,
                'thumbnail_path' => $thumbnailPath,
                'uploaded_by' => auth()->id(),
                'order' => $request->order ?? 0,
                'is_featured' => $request->boolean('is_featured'),
                'metadata' => isset($metadata) ? $metadata : []
            ]);

            // Log activity
            ActivityLog::logCreated($gallery, "Created gallery item: {$gallery->title}");

            return redirect()->route('admin.gallery.show', $gallery)
                ->with('success', 'Gallery item created successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the gallery item. Please try again.');
        }
    }

    /**
     * Display the specified gallery item
     */
    public function show(Gallery $gallery)
    {
        $gallery->load('uploader');
        
        // Log viewing activity
        ActivityLog::logViewed($gallery, "Viewed gallery item: {$gallery->title}");

        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified gallery item
     */
    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery item
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'remove_image' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $oldData = $gallery->toArray();

            // Handle image update
            $imagePath = $gallery->image_path;
            $thumbnailPath = $gallery->thumbnail_path;

            if ($request->boolean('remove_image')) {
                $this->imageService->deleteImage($imagePath, $thumbnailPath);
                $imagePath = null;
                $thumbnailPath = null;
            } elseif ($request->hasFile('image')) {
                // Remove old images
                $this->imageService->deleteImage($imagePath, $thumbnailPath);

                // Process new image
                $result = $this->imageService->processImage(
                    $request->file('image'),
                    'gallery',
                    [300, 300]
                );
                
                if ($result['success']) {
                    $imagePath = $result['original_path'];
                    $thumbnailPath = $result['thumbnail_path'];
                } else {
                    return back()->with('error', 'Image processing failed: ' . $result['error']);
                }
            }

            $gallery->update([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'image_path' => $imagePath,
                'thumbnail_path' => $thumbnailPath,
                'order' => $request->order ?? 0,
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Log activity
            ActivityLog::logUpdated($gallery, $oldData, "Updated gallery item: {$gallery->title}");

            return redirect()->route('admin.gallery.show', $gallery)
                ->with('success', 'Gallery item updated successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the gallery item. Please try again.');
        }
    }

    /**
     * Remove the specified gallery item
     */
    public function destroy(Gallery $gallery)
    {
        try {
            // Remove associated files using ImageService
            $this->imageService->deleteImage($gallery->image_path, $gallery->thumbnail_path);

            // Log activity before deletion
            ActivityLog::logDeleted($gallery, "Deleted gallery item: {$gallery->title}");

            $gallery->delete();

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Gallery item deleted successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the gallery item. Please try again.');
        }
    }

    /**
     * Toggle featured status of gallery item
     */
    public function toggleFeatured(Gallery $gallery)
    {
        try {
            $oldData = ['is_featured' => $gallery->is_featured];
            
            $gallery->update(['is_featured' => !$gallery->is_featured]);

            $action = $gallery->is_featured ? 'Featured' : 'Unfeatured';

            // Log activity
            ActivityLog::logUpdated($gallery, $oldData, "{$action} gallery item: {$gallery->title}");

            return response()->json(['success' => true, 'is_featured' => $gallery->is_featured]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Bulk operations on gallery items
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:feature,unfeature,delete',
            'gallery_ids' => 'required|array',
            'gallery_ids.*' => 'exists:galleries,id'
        ]);

        try {
            $galleries = Gallery::whereIn('id', $request->gallery_ids)->get();
            $count = 0;

            foreach ($galleries as $gallery) {
                switch ($request->action) {
                    case 'feature':
                        if (!$gallery->is_featured) {
                            $gallery->update(['is_featured' => true]);
                            $count++;
                        }
                        break;
                    case 'unfeature':
                        if ($gallery->is_featured) {
                            $gallery->update(['is_featured' => false]);
                            $count++;
                        }
                        break;
                    case 'delete':
                        $this->imageService->deleteImage($gallery->image_path, $gallery->thumbnail_path);
                        $gallery->delete();
                        $count++;
                        break;
                }
            }

            // Log bulk activity
            ActivityLog::logActivity(
                'bulk_' . $request->action,
                "Bulk {$request->action} on {$count} gallery items"
            );

            return response()->json([
                'success' => true,
                'message' => "{$count} gallery items were processed successfully."
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Generate thumbnail with specific dimensions
     */
    private function generateThumbnail($imagePath, $width = 300, $height = 300, $prefix = 'thumb_')
    {
        try {
            $originalPath = storage_path('app/public/' . $imagePath);
            $pathInfo = pathinfo($imagePath);
            
            $thumbnailFilename = $prefix . $pathInfo['filename'] . '.' . $pathInfo['extension'];
            $thumbnailPath = $pathInfo['dirname'] . '/' . $thumbnailFilename;
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            
            // Generate thumbnail
            $img = Image::read($originalPath);
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $img->save($thumbnailFullPath);
            
            return $thumbnailPath;
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return $imagePath; // Return original if thumbnail fails
        }
    }

    /**
     * Generate multiple thumbnail sizes
     */
    private function generateMultipleThumbnails($imagePath)
    {
        $thumbnails = [];
        
        // Generate different sizes
        $sizes = [
            'small' => ['width' => 150, 'height' => 150, 'prefix' => 'small_'],
            'medium' => ['width' => 300, 'height' => 300, 'prefix' => 'medium_'],
            'large' => ['width' => 600, 'height' => 600, 'prefix' => 'large_'],
        ];
        
        foreach ($sizes as $size => $config) {
            $thumbnails[$size] = $this->generateThumbnail(
                $imagePath, 
                $config['width'], 
                $config['height'], 
                $config['prefix']
            );
        }
        
        return $thumbnails;
    }

    /**
     * Optimize image quality and size
     */
    private function optimizeImage($imagePath, $quality = 85)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            $img = Image::read($fullPath);
            
            // Optimize for web
            $img->save($fullPath, $quality);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage());
            return false;
        }
    }
}