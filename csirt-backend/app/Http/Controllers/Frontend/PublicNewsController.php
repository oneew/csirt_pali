<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class PublicNewsController extends Controller
{
    /**
     * Show the news listing page
     */
    public function index(Request $request)
    {
        try {
            // Get featured news
            $featuredNews = News::published()
                ->featured()
                ->with('author')
                ->latest('published_at')
                ->limit(2)
                ->get();

            // Get recent news with pagination
            $recentNews = News::published()
                ->with('author')
                ->latest('published_at')
                ->paginate(6);

            // Get popular categories with article counts
            $popularCategories = News::published()
                ->selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(4)
                ->get();

            // Get recent articles for sidebar
            $sidebarNews = News::published()
                ->with('author')
                ->latest('published_at')
                ->limit(3)
                ->get();

            return view('frontend.news-list', compact(
                'featuredNews',
                'recentNews', 
                'popularCategories',
                'sidebarNews'
            ));
        } catch (\Exception $e) {
            // Fallback to simple template with empty data
            return view('frontend.news-list', [
                'featuredNews' => collect(),
                'recentNews' => null,
                'popularCategories' => collect(),
                'sidebarNews' => collect()
            ]);
        }
    }

    /**
     * Show a specific news article
     */
    public function show(News $news)
    {
        try {
            // Make sure news is published
            if (!$news->isPublished()) {
                abort(404);
            }

            // Increment view count
            $news->increment('views');

            // Get related news
            $relatedNews = News::published()
                ->where('id', '!=', $news->id)
                ->where('category', $news->category)
                ->latest('published_at')
                ->limit(3)
                ->get();

            // If no related news in same category, get recent news
            if ($relatedNews->count() < 3) {
                $relatedNews = News::published()
                    ->where('id', '!=', $news->id)
                    ->latest('published_at')
                    ->limit(3)
                    ->get();
            }

            return view('frontend.news-detail', compact('news', 'relatedNews'));
        } catch (\Exception $e) {
            // Fallback to news detail template without data
            return view('frontend.news-detail', [
                'news' => null,
                'relatedNews' => collect()
            ]);
        }
    }

    /**
     * Show news by category
     */
    public function category($category)
    {
        try {
            $news = News::published()
                ->where('category', $category)
                ->with('author')
                ->latest('published_at')
                ->paginate(10);

            $categoryName = ucfirst(str_replace('_', ' ', $category));

            // Get popular categories for sidebar
            $popularCategories = News::published()
                ->selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(4)
                ->get();

            // Get recent articles for sidebar
            $sidebarNews = News::published()
                ->with('author')
                ->latest('published_at')
                ->limit(3)
                ->get();

            return view('frontend.news-list', compact(
                'news', 
                'category', 
                'categoryName',
                'popularCategories',
                'sidebarNews'
            ));
        } catch (\Exception $e) {
            // Fallback to simple template
            return view('frontend.news-list', [
                'recentNews' => $news ?? collect(),
                'category' => $category ?? null,
                'categoryName' => $categoryName ?? 'News',
                'popularCategories' => collect(),
                'sidebarNews' => collect(),
                'featuredNews' => collect()
            ]);
        }
    }
}