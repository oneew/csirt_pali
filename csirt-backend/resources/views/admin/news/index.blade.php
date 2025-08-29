@extends('admin.layouts.app')

@section('title', 'News Management')
@section('page-title', 'News Management')

@section('content')
<div class="admin-header">
    <h1 class="page-title">News Management</h1>
    <div class="admin-actions">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Create Article
        </a>
    </div>
</div>

<div class="admin-filters">
    <div class="filter-row">
        <div class="filter-group">
            <select id="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
            </select>
        </div>
        <div class="filter-group">
            <select id="categoryFilter" class="form-select">
                <option value="">All Categories</option>
                <option value="security_alert">Security Alert</option>
                <option value="threat_intelligence">Threat Intelligence</option>
                <option value="vulnerability">Vulnerability</option>
                <option value="incident_report">Incident Report</option>
                <option value="best_practices">Best Practices</option>
                <option value="general">General</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" id="searchFilter" class="form-input" placeholder="Search articles...">
        </div>
    </div>
</div>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Author</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($news ?? [] as $article)
                <tr data-status="{{ $article->status }}" data-category="{{ $article->category }}">
                    <td>
                        <div class="table-primary">
                            <h4>{{ $article->title }}</h4>
                            @if($article->excerpt)
                                <p class="table-secondary">{{ Str::limit($article->excerpt, 80) }}</p>
                            @endif
                            @if($article->is_featured)
                                <span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-outline">{{ ucfirst(str_replace('_', ' ', $article->category)) }}</span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $article->status === 'published' ? 'success' : ($article->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $article->priority === 'critical' ? 'danger' : ($article->priority === 'high' ? 'warning' : ($article->priority === 'medium' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($article->priority) }}
                        </span>
                    </td>
                    <td>
                        <div class="table-user">
                            @if($article->author)
                                <span>{{ $article->author->full_name }}</span>
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($article->published_at)
                            <span>{{ $article->published_at->format('M d, Y') }}</span>
                            <small class="text-muted d-block">{{ $article->published_at->format('H:i') }}</small>
                        @else
                            <span class="text-muted">Not published</span>
                        @endif
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('admin.news.show', $article) }}" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($article->status === 'draft')
                                <form action="{{ route('admin.news.publish', $article) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Publish">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            @elseif($article->status === 'published')
                                <form action="{{ route('admin.news.unpublish', $article) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary" title="Unpublish">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.news.featured', $article) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $article->is_featured ? 'btn-warning' : 'btn-outline-warning' }}" title="{{ $article->is_featured ? 'Remove Featured' : 'Mark Featured' }}">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-newspaper text-muted"></i>
                            <h4 class="text-muted">No News Articles Found</h4>
                            <p class="text-muted">Create your first news article to get started.</p>
                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Create First Article
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($news) && $news->hasPages())
    <div class="admin-pagination">
        {{ $news->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Filter functionality for admin table
    document.getElementById('statusFilter').addEventListener('change', filterNews);
    document.getElementById('categoryFilter').addEventListener('change', filterNews);
    document.getElementById('searchFilter').addEventListener('input', filterNews);

    function filterNews() {
        const statusFilter = document.getElementById('statusFilter').value;
        const categoryFilter = document.getElementById('categoryFilter').value;
        const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
        const tableRows = document.querySelectorAll('.admin-table tbody tr');

        tableRows.forEach(row => {
            if (row.querySelector('.empty-state')) {
                return; // Skip empty state row
            }
            
            const status = row.dataset.status;
            const category = row.dataset.category;
            const title = row.querySelector('.table-primary h4').textContent.toLowerCase();
            const excerpt = row.querySelector('.table-secondary');
            const excerptText = excerpt ? excerpt.textContent.toLowerCase() : '';
            
            const showByStatus = !statusFilter || status === statusFilter;
            const showByCategory = !categoryFilter || category === categoryFilter;
            const showBySearch = !searchFilter || title.includes(searchFilter) || excerptText.includes(searchFilter);
            
            if (showByStatus && showByCategory && showBySearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty state
        const visibleRows = Array.from(tableRows).filter(row => {
            return row.style.display !== 'none' && !row.querySelector('.empty-state');
        });
        
        const emptyStateRow = document.querySelector('.admin-table tbody tr .empty-state');
        if (emptyStateRow) {
            const parentRow = emptyStateRow.closest('tr');
            if (visibleRows.length === 0 && (statusFilter || categoryFilter || searchFilter)) {
                parentRow.style.display = '';
                emptyStateRow.querySelector('h4').textContent = 'No Articles Match Your Filters';
                emptyStateRow.querySelector('p').textContent = 'Try adjusting your search criteria.';
            } else if (visibleRows.length === 0) {
                parentRow.style.display = '';
            } else {
                parentRow.style.display = 'none';
            }
        }
    }
</script>
@endpush