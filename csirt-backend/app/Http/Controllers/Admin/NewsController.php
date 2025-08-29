<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\News;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Display a listing of news articles
     */
    public function index(Request $request)
    {
        $query = News::with('author');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $news = $query->paginate(15);
        $authors = User::whereIn('role', ['admin', 'operator'])->orderBy('first_name')->get();

        return view('admin.news.index', compact('news', 'authors'));
    }

    /**
     * Show the form for creating a new news article
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created news article
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:security_alert,threat_intelligence,vulnerability,incident_report,best_practices,general',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:5120', // 5MB max
            'tags' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle featured image upload
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = $request->file('featured_image')->store('news', 'public');
        }

        // Process tags
        $tags = [];
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $tags = array_filter($tags); // Remove empty values
        }

        // Set publish date
        $publishDate = null;
        if ($request->status === 'published') {
            $publishDate = $request->publish_date ? $request->publish_date : now();
        }

        $news = News::create([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'author_id' => auth()->id(),
            'featured_image' => $featuredImagePath,
            'tags' => $tags,
            'published_at' => $publishDate,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        // Log activity
        ActivityLog::logCreated($news, "Created news article: {$news->title}");

        // Create notifications if published
        if ($news->status === 'published') {
            $this->notifyUsersOfNewNews($news);
        }

        return redirect()->route('admin.news.show', $news)
            ->with('success', 'News article created successfully.');
    }

    /**
     * Display the specified news article
     */
    public function show(News $news)
    {
        $news->load('author');
        
        // Log viewing activity
        ActivityLog::logViewed($news, "Viewed news article: {$news->title}");

        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news article
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified news article
     */
    public function update(Request $request, News $news)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $news->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:security_alert,threat_intelligence,vulnerability,incident_report,best_practices,general',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:draft,published,archived',
            'featured_image' => 'nullable|image|max:5120',
            'tags' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'remove_featured_image' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $oldData = $news->toArray();
        $wasPublished = $news->status === 'published';

        // Handle featured image
        $featuredImagePath = $news->featured_image;
        if ($request->boolean('remove_featured_image')) {
            if ($featuredImagePath) {
                Storage::disk('public')->delete($featuredImagePath);
            }
            $featuredImagePath = null;
        } elseif ($request->hasFile('featured_image')) {
            if ($featuredImagePath) {
                Storage::disk('public')->delete($featuredImagePath);
            }
            $featuredImagePath = $request->file('featured_image')->store('news', 'public');
        }

        // Process tags
        $tags = [];
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $tags = array_filter($tags);
        }

        // Handle publish date
        $publishDate = $news->published_at;
        if ($request->status === 'published') {
            if (!$wasPublished || $request->filled('publish_date')) {
                $publishDate = $request->publish_date ? $request->publish_date : now();
            }
        } elseif ($request->status === 'draft') {
            $publishDate = null;
        }

        $news->update([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'featured_image' => $featuredImagePath,
            'tags' => $tags,
            'published_at' => $publishDate,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        // Log activity
        ActivityLog::logUpdated($news, $oldData, "Updated news article: {$news->title}");

        // Notify users if just published
        if (!$wasPublished && $news->status === 'published') {
            $this->notifyUsersOfNewNews($news);
        }

        return redirect()->route('admin.news.show', $news)
            ->with('success', 'News article updated successfully.');
    }

    /**
     * Remove the specified news article
     */
    public function destroy(News $news)
    {
        // Remove featured image
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        // Log activity before deletion
        ActivityLog::logDeleted($news, "Deleted news article: {$news->title}");

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News article deleted successfully.');
    }

    /**
     * Publish a news article
     */
    public function publish(News $news)
    {
        $oldData = ['status' => $news->status, 'published_at' => $news->published_at];
        
        $news->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        // Log activity
        ActivityLog::logUpdated($news, $oldData, "Published news article: {$news->title}");

        // Notify users
        $this->notifyUsersOfNewNews($news);

        return response()->json(['success' => true]);
    }

    /**
     * Unpublish a news article
     */
    public function unpublish(News $news)
    {
        $oldData = ['status' => $news->status];
        
        $news->update(['status' => 'draft']);

        // Log activity
        ActivityLog::logUpdated($news, $oldData, "Unpublished news article: {$news->title}");

        return response()->json(['success' => true]);
    }

    /**
     * Archive a news article
     */
    public function archive(News $news)
    {
        $oldData = ['status' => $news->status];
        
        $news->update(['status' => 'archived']);

        // Log activity
        ActivityLog::logUpdated($news, $oldData, "Archived news article: {$news->title}");

        return response()->json(['success' => true]);
    }

    /**
     * Toggle featured status of a news article
     */
    public function toggleFeatured(News $news)
    {
        $oldData = ['is_featured' => $news->is_featured];
        
        $news->update(['is_featured' => !$news->is_featured]);

        $action = $news->is_featured ? 'Featured' : 'Unfeatured';

        // Log activity
        ActivityLog::logUpdated($news, $oldData, "{$action} news article: {$news->title}");

        return response()->json(['success' => true]);
    }

    /**
     * Duplicate a news article
     */
    public function duplicate(News $news)
    {
        $newNews = $news->replicate();
        $newNews->title = $news->title . ' (Copy)';
        $newNews->slug = null; // Will be auto-generated
        $newNews->status = 'draft';
        $newNews->published_at = null;
        $newNews->views_count = 0;
        $newNews->is_featured = false;
        $newNews->save();

        // Log activity
        ActivityLog::logCreated($newNews, "Duplicated news article: {$newNews->title}");

        return redirect()->route('admin.news.edit', $newNews)
            ->with('success', 'News article duplicated successfully.');
    }

    /**
     * Bulk operations on news articles
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,archive,delete',
            'news_ids' => 'required|array',
            'news_ids.*' => 'exists:news,id'
        ]);

        $newsArticles = News::whereIn('id', $request->news_ids)->get();
        $count = 0;

        foreach ($newsArticles as $news) {
            switch ($request->action) {
                case 'publish':
                    if ($news->status !== 'published') {
                        $news->update(['status' => 'published', 'published_at' => now()]);
                        $this->notifyUsersOfNewNews($news);
                        $count++;
                    }
                    break;
                case 'unpublish':
                    if ($news->status === 'published') {
                        $news->update(['status' => 'draft']);
                        $count++;
                    }
                    break;
                case 'archive':
                    if ($news->status !== 'archived') {
                        $news->update(['status' => 'archived']);
                        $count++;
                    }
                    break;
                case 'delete':
                    if ($news->featured_image) {
                        Storage::disk('public')->delete($news->featured_image);
                    }
                    $news->delete();
                    $count++;
                    break;
            }
        }

        // Log bulk activity
        ActivityLog::logActivity(
            'bulk_' . $request->action,
            "Bulk {$request->action} on {$count} news articles"
        );

        return response()->json([
            'success' => true,
            'message' => "{$count} news articles were {$request->action}d successfully."
        ]);
    }

    /**
     * Export news articles to CSV
     */
    public function export(Request $request)
    {
        $query = News::with('author');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // ... other filters ...

        $newsArticles = $query->get();

        $csvData = [];
        $csvData[] = [
            'Title', 'Slug', 'Category', 'Priority', 'Status', 'Author',
            'Published At', 'Views', 'Is Featured', 'Created At'
        ];

        foreach ($newsArticles as $news) {
            $csvData[] = [
                $news->title,
                $news->slug,
                $news->category,
                $news->priority,
                $news->status,
                $news->author->full_name,
                $news->published_at ? $news->published_at->format('Y-m-d H:i:s') : '',
                $news->views_count,
                $news->is_featured ? 'Yes' : 'No',
                $news->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'news_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $handle = fopen('php://temp', 'w');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Notify users of new published news
     */
    private function notifyUsersOfNewNews(News $news)
    {
        // For high/critical priority news, notify all users
        if (in_array($news->priority, ['high', 'critical'])) {
            Notification::createForAllUsers(
                'Important News Published',
                "New {$news->priority} priority news: {$news->title}",
                $news->priority === 'critical' ? 'error' : 'warning',
                'news',
                ['news_id' => $news->id]
            );
        } else {
            // For normal priority, notify admins and operators only
            $users = User::whereIn('role', ['admin', 'operator'])->get();
            foreach ($users as $user) {
                Notification::createNewsNotification($user->id, $news);
            }
        }
    }
}