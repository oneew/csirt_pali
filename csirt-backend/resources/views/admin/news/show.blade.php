@extends('admin.layouts.app')

@section('title', 'News Article')
@section('page-title', 'News Article')

@section('content')
<div class="admin-header">
    <h1 class="page-title">{{ $news->title }}</h1>
    <div class="admin-actions">
        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i>
            Edit
        </a>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to List
        </a>
    </div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="article-badges">
                <span class="badge badge-{{ $news->status === 'published' ? 'success' : ($news->status === 'draft' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($news->status) }}
                </span>
                <span class="badge badge-{{ $news->priority === 'critical' ? 'danger' : ($news->priority === 'high' ? 'warning' : ($news->priority === 'medium' ? 'info' : 'secondary')) }}">
                    {{ ucfirst($news->priority) }}
                </span>
                <span class="badge badge-outline">{{ ucfirst(str_replace('_', ' ', $news->category)) }}</span>
                @if($news->is_featured)
                    <span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>
                @endif
            </div>
        </div>
        
        <div class="admin-card-body">
            <!-- Article Information -->
            <div class="info-section">
                <h3 class="section-title">Article Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Author:</label>
                        <span>{{ $news->author ? $news->author->full_name : 'Unknown' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Created:</label>
                        <span>{{ $news->created_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                    @if($news->published_at)
                        <div class="info-item">
                            <label>Published:</label>
                            <span>{{ $news->published_at->format('F j, Y \a\t g:i A') }}</span>
                        </div>
                    @endif
                    <div class="info-item">
                        <label>Views:</label>
                        <span>{{ $news->views_count ?? 0 }}</span>
                    </div>
                    <div class="info-item">
                        <label>Reading Time:</label>
                        <span>{{ $news->reading_time ?? 'N/A' }}</span>
                    </div>
                    @if($news->slug)
                        <div class="info-item">
                            <label>Slug:</label>
                            <span><code>{{ $news->slug }}</code></span>
                        </div>
                    @endif
                </div>
            </div>

            @if($news->excerpt)
                <!-- Excerpt -->
                <div class="info-section">
                    <h3 class="section-title">Excerpt</h3>
                    <div class="excerpt-content">
                        {{ $news->excerpt }}
                    </div>
                </div>
            @endif

            @if($news->featured_image)
                <!-- Featured Image -->
                <div class="info-section">
                    <h3 class="section-title">Featured Image</h3>
                    <div class="image-preview">
                        <img src="{{ $news->featured_image_url }}" alt="{{ $news->title }}" class="img-fluid">
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="info-section">
                <h3 class="section-title">Content</h3>
                <div class="article-content">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>

            @if($news->tags && count($news->tags) > 0)
                <!-- Tags -->
                <div class="info-section">
                    <h3 class="section-title">Tags</h3>
                    <div class="tags-list">
                        @foreach($news->tags as $tag)
                            <span class="tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <div class="admin-card-footer">
            <div class="action-buttons">
                @if($news->status === 'draft')
                    <form action="{{ route('admin.news.publish', $news) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i>
                            Publish Article
                        </button>
                    </form>
                @elseif($news->status === 'published')
                    <form action="{{ route('admin.news.unpublish', $news) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish Article
                        </button>
                    </form>
                @endif
                
                <form action="{{ route('admin.news.featured', $news) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-{{ $news->is_featured ? 'secondary' : 'warning' }}">
                        <i class="fas fa-star"></i>
                        {{ $news->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                    </button>
                </form>

                <form action="{{ route('admin.news.duplicate', $news) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-copy"></i>
                        Duplicate Article
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection