<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Show the services page
     */
    public function index(Request $request)
    {
        try {
            // Get featured services
            $featuredServices = Service::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('order')
                ->limit(3)
                ->get();

            // Get all services with pagination
            $services = Service::where('is_active', true)
                ->when($request->filled('category'), function ($query) use ($request) {
                    return $query->where('category', $request->category);
                })
                ->orderBy('order')
                ->paginate(9);

            return view('frontend.services', compact('featuredServices', 'services'));
        } catch (\Exception $e) {
            // Fallback to services template with empty data
            return view('frontend.services', [
                'featuredServices' => collect(),
                'services' => null
            ]);
        }
    }

    /**
     * Show a specific service
     */
    public function show(Service $service)
    {
        // Make sure service is active
        if (!$service->is_active) {
            abort(404);
        }

        // Get related services
        $relatedServices = Service::where('is_active', true)
            ->where('id', '!=', $service->id)
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('frontend.service-detail', compact('service', 'relatedServices'));
    }
}