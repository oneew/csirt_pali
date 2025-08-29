@extends('admin.layouts.app')

@section('title', 'Create News Article')
@section('page-title', 'Create News Article')

@section('content')
<div class="admin-header">
    <h1 class="page-title">Create News Article</h1>
    <div class="admin-actions">
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to News
        </a>
    </div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="admin-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" id="title" name="title" class="form-input" value="{{ old('title') }}" required>
                        @error('title')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-input" value="{{ old('slug') }}" 
                               placeholder="Auto-generated from title if left empty">
                        <small class="form-text">URL-friendly version of the title. Leave empty to auto-generate.</small>
                        @error('slug')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Category *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="security_alert" {{ old('category') == 'security_alert' ? 'selected' : '' }}>Security Alert</option>
                            <option value="threat_intelligence" {{ old('category') == 'threat_intelligence' ? 'selected' : '' }}>Threat Intelligence</option>
                            <option value="vulnerability" {{ old('category') == 'vulnerability' ? 'selected' : '' }}>Vulnerability</option>
                            <option value="incident_report" {{ old('category') == 'incident_report' ? 'selected' : '' }}>Incident Report</option>
                            <option value="best_practices" {{ old('category') == 'best_practices' ? 'selected' : '' }}>Best Practices</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="priority" class="form-label">Priority *</label>
                        <select id="priority" name="priority" class="form-select" required>
                            <option value="">Select Priority</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="publish_date" class="form-label">Publish Date</label>
                        <input type="datetime-local" id="publish_date" name="publish_date" class="form-input" 
                               value="{{ old('publish_date') }}">
                        <small class="form-text">Leave empty to publish immediately when status is set to published.</small>
                        @error('publish_date')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" id="featured_image" name="featured_image" class="form-input" accept="image/*">
                        <small class="form-text">Recommended size: 1200x600 pixels. Max file size: 5MB.</small>
                        @error('featured_image')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-checkbox">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label for="is_featured">Featured Article</label>
                        </div>
                        <small class="form-text">Featured articles appear prominently on the homepage.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" class="form-textarea" rows="3" 
                              placeholder="Brief summary of the article (optional)">{{ old('excerpt') }}</textarea>
                    <small class="form-text">A short description that appears in article listings and search results.</small>
                    @error('excerpt')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Content *</label>
                    <textarea id="content" name="content" class="form-textarea" rows="12" required>{{ old('content') }}</textarea>
                    <small class="form-text">The main content of your news article.</small>
                    @error('content')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" id="tags" name="tags" class="form-input" value="{{ old('tags') }}" 
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
                        Create Article
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slugField = document.getElementById('slug');
        if (!slugField.value || slugField.dataset.autoGenerated) {
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugField.value = slug;
            slugField.dataset.autoGenerated = 'true';
        }
    });
    
    // Mark as manually edited when slug is changed directly
    document.getElementById('slug').addEventListener('input', function() {
        if (this.value) {
            this.dataset.autoGenerated = 'false';
        }
    });
</script>
@endpush