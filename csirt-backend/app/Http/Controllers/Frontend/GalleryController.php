<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Show the gallery page
     */
    public function index(Request $request)
    {
        try {
            // Get gallery items with pagination
            $galleryItems = Gallery::with('uploader')
                ->when($request->filled('category'), function ($query) use ($request) {
                    return $query->where('category', $request->category);
                })
                ->ordered()
                ->paginate(12);

            // Get unique categories for filter
            $categories = Gallery::distinct()
                ->pluck('category')
                ->filter()
                ->sort();

            return view('frontend.gallery', compact('galleryItems', 'categories'));
        } catch (\Exception $e) {
            // Fallback to gallery template with empty data
            return view('frontend.gallery', [
                'galleryItems' => null,
                'categories' => collect()
            ]);
        }
    }

    /**
     * Show a specific gallery item
     */
    public function show(Gallery $gallery)
    {
        try {
            // Get related gallery items
            $relatedItems = Gallery::where('id', '!=', $gallery->id)
                ->where('category', $gallery->category)
                ->ordered()
                ->limit(6)
                ->get();

            return view('frontend.gallery-item', compact('gallery', 'relatedItems'));
        } catch (\Exception $e) {
            return view('frontend.gallery-item', [
                'gallery' => $gallery,
                'relatedItems' => collect()
            ]);
        }
    }
}