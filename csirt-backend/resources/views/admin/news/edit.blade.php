@extends('admin.layouts.app')

@section('title', 'Edit News Article')
@section('page-title', 'Edit News Article')

@section('content')
<div class="admin-header">
    <h1 class="page-title">Edit News Article</h1>
    <div class="admin-actions">
        <a href="{{ route('admin.news.show', $news) }}" class="btn btn-info">
            <i class="fas fa-eye"></i>
            View Article
        </a>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to News
        </a>
    </div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="admin-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" id="title" name="title" class="form-input" value="{{ old('title', $news->title) }}" required>
                        @error('title')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">Slug *</label>
                        <input type="text" id="slug" name="slug" class="form-input" value="{{ old('slug', $news->slug) }}" required>
                        <small class="form-text">URL-friendly version of the title.</small>
                        @error('slug')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Category *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="security_alert" {{ old('category', $news->category) == 'security_alert' ? 'selected' : '' }}>Security Alert</option>
                            <option value="threat_intelligence" {{ old('category', $news->category) == 'threat_intelligence' ? 'selected' : '' }}>Threat Intelligence</option>
                            <option value="vulnerability" {{ old('category', $news->category) == 'vulnerability' ? 'selected' : '' }}>Vulnerability</option>
                            <option value="incident_report" {{ old('category', $news->category) == 'incident_report' ? 'selected' : '' }}>Incident Report</option>
                            <option value="best_practices" {{ old('category', $news->category) == 'best_practices' ? 'selected' : '' }}>Best Practices</option>
                            <option value="general" {{ old('category', $news->category) == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="priority" class="form-label">Priority *</label>
                        <select id="priority" name="priority" class="form-select" required>
                            <option value="">Select Priority</option>
                            <option value="low" {{ old('priority', $news->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $news->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $news->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority', $news->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $news->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="publish_date" class="form-label">Publish Date</label>
                        <input type="datetime-local" id="publish_date" name="publish_date" class="form-input" 
                               value="{{ old('publish_date', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">
                        <small class="form-text">Leave empty to publish immediately when status is set to published.</small>
                        @error('publish_date')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" id="featured_image" name="featured_image" class="form-input" accept="image/*">
                        @if($news->featured_image)
                            <div class="current-image mt-2">
                                <div class="image-preview">
                                    <img src="{{ $news->featured_image_url }}" alt="Current featured image" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                                <div class="form-checkbox mt-2">
                                    <input type="checkbox" id="remove_featured_image" name="remove_featured_image" value="1">
                                    <label for="remove_featured_image">Remove current image</label>
                                </div>
                            </div>
                        @endif
                        <small class="form-text">Upload a new image to replace the current one. Recommended size: 1200x600 pixels. Max file size: 5MB.</small>
                        @error('featured_image')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-checkbox">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                            <label for="is_featured">Featured Article</label>
                        </div>
                        <small class="form-text">Featured articles appear prominently on the homepage.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" class="form-textarea" rows="3" 
                              placeholder="Brief summary of the article">{{ old('excerpt', $news->excerpt) }}</textarea>
                    <small class="form-text">A short description that appears in article listings and search results.</small>
                    @error('excerpt')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Content *</label>
                    <textarea id="content" name="content" class="form-textarea" rows="12" required>{{ old('content', $news->content) }}</textarea>
                    <small class="form-text">The main content of your news article.</small>
                    @error('content')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" id="tags" name="tags" class="form-input" 
                           value="{{ old('tags', is_array($news->tags) ? implode(', ', $news->tags) : '') }}" 
                           placeholder="Enter tags separated by commas">
                    <small class="form-text">Example: cybersecurity, malware, security alert</small>
                    @error('tags')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="admin-card-footer">
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Article
                    </button>
                    <a href="{{ route('admin.news.show', $news) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    @if($news->status === 'draft')
                        <button type="submit" name="status" value="published" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i>
                            Save & Publish
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-generate slug from title if slug field is empty
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slugField = document.getElementById('slug');
        if (!slugField.value.trim()) {
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugField.value = slug;
        }
    });
</script>
@endpush