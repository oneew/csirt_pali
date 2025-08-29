<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($news) ? $news->title . ' - ' : '' }}CSIRT PALI</title>
    <meta name="description" content="{{ isset($news) && $news->excerpt ? $news->excerpt : 'CSIRT PALI News Article' }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/news.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
</head>
<body>
    <!-- Main Website Content -->
    <div class="main-website" id="mainWebsite">
        <header class="header transparent">
            <nav class="navbar">
                <div class="nav-container">
                    <div class="nav-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('frontend/images/Logo.png') }}" alt="CSIRT_Pali Logo" class="logo">
                        </a>
                    </div>
                    
                    <button class="mobile-menu-toggle" id="mobile-menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    
                    <div class="nav-menu" id="nav-menu">
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('profile') }}" class="nav-link">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('services') }}" class="nav-link">Services</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('gallery') }}" class="nav-link">Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('news.index') }}" class="nav-link active">News</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Breadcrumb -->
        <section class="breadcrumb">
            <div class="container">
                <nav class="breadcrumb-nav">
                    <a href="{{ route('home') }}" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <i class="fas fa-chevron-right breadcrumb-separator"></i>
                    <a href="{{ route('news.index') }}" class="breadcrumb-link">News</a>
                    @if(isset($news))
                    <i class="fas fa-chevron-right breadcrumb-separator"></i>
                    <span class="breadcrumb-current">{{ Str::limit($news->title, 50) }}</span>
                    @endif
                </nav>
            </div>
        </section>

        @if(isset($news))
        <!-- Article Content -->
        <article class="article-detail">
            <div class="container">
                <div class="article-content">
                    <div class="article-main">
                        <!-- Article Header -->
                        <header class="article-header">
                            <div class="article-meta-top">
                                <span class="article-category {{ $news->category }}">
                                    {{ ucfirst(str_replace('_', ' ', $news->category)) }}
                                </span>
                                @if($news->priority && $news->priority !== 'low')
                                <span class="article-priority {{ $news->priority }}">
                                    <i class="fas fa-flag"></i>
                                    {{ ucfirst($news->priority) }} Priority
                                </span>
                                @endif
                                @if($news->is_featured)
                                <span class="article-featured">
                                    <i class="fas fa-star"></i>
                                    Featured
                                </span>
                                @endif
                            </div>
                            <h1 class="article-title">{{ $news->title }}</h1>
                            @if($news->excerpt)
                            <p class="article-excerpt">{{ $news->excerpt }}</p>
                            @endif
                            <div class="article-meta">
                                <div class="article-author">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $news->author->full_name ?? $news->author_name ?? 'CSIRT PALI Team' }}</span>
                                </div>
                                <div class="article-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $news->published_at ? $news->published_at->format('F j, Y') : $news->created_at->format('F j, Y') }}</span>
                                </div>
                                <div class="article-views">
                                    <i class="fas fa-eye"></i>
                                    <span>{{ number_format($news->views_count ?? 0) }} views</span>
                                </div>
                                @if($news->reading_time)
                                <div class="article-reading-time">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $news->reading_time }}</span>
                                </div>
                                @endif
                            </div>
                        </header>

                        <!-- Featured Image -->
                        @if($news->featured_image)
                        <div class="article-image">
                            <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}">
                        </div>
                        @endif

                        <!-- Article Body -->
                        <div class="article-body">
                            {!! nl2br(e($news->content)) !!}
                        </div>

                        <!-- Article Tags -->
                        @if($news->tags && count($news->tags) > 0)
                        <div class="article-tags">
                            <h4>Tags:</h4>
                            <div class="tags-list">
                                @foreach($news->tags as $tag)
                                <span class="tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Article Actions -->
                        <div class="article-actions">
                            <button class="action-btn share-btn" onclick="shareArticle()">
                                <i class="fas fa-share-alt"></i>
                                Share
                            </button>
                            <button class="action-btn print-btn" onclick="window.print()">
                                <i class="fas fa-print"></i>
                                Print
                            </button>
                            <a href="{{ route('news.index') }}" class="action-btn back-btn">
                                <i class="fas fa-arrow-left"></i>
                                Back to News
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <aside class="article-sidebar">
                        @if(isset($relatedNews) && $relatedNews->count() > 0)
                        <!-- Related Articles -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Related Articles</h3>
                            <div class="related-articles">
                                @foreach($relatedNews as $related)
                                <article class="related-article">
                                    <div class="related-thumbnail">
                                        <img src="{{ $related->featured_image ? asset('storage/' . $related->featured_image) : asset('frontend/images/bg2.png') }}" alt="{{ $related->title }}">
                                    </div>
                                    <div class="related-content">
                                        <h4 class="related-title">
                                            <a href="{{ route('news.show', $related->slug) }}">{{ Str::limit($related->title, 60) }}</a>
                                        </h4>
                                        <span class="related-date">{{ $related->published_at ? $related->published_at->format('M j, Y') : $related->created_at->format('M j, Y') }}</span>
                                    </div>
                                </article>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Quick Actions</h3>
                            <div class="quick-actions">
                                <a href="{{ route('contact') }}" class="quick-action">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Report Incident
                                </a>
                                <a href="{{ route('news.index') }}" class="quick-action">
                                    <i class="fas fa-newspaper"></i>
                                    All News
                                </a>
                                <a href="{{ route('services') }}" class="quick-action">
                                    <i class="fas fa-shield-alt"></i>
                                    Our Services
                                </a>
                            </div>
                        </div>

                        <!-- Newsletter Signup -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Stay Updated</h3>
                            <div class="newsletter-widget">
                                <p>Get the latest cybersecurity news and alerts delivered to your inbox.</p>
                                <form class="newsletter-form" id="newsletterForm">
                                    <input type="email" class="newsletter-input" placeholder="Your email address" required>
                                    <button type="submit" class="newsletter-btn">
                                        <i class="fas fa-paper-plane"></i>
                                        Subscribe
                                    </button>
                                </form>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </article>
        @else
        <!-- Article Not Found -->
        <section class="article-not-found">
            <div class="container">
                <div class="not-found-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h1>Article Not Found</h1>
                    <p>The article you're looking for doesn't exist or has been removed.</p>
                    <a href="{{ route('news.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        Back to News
                    </a>
                </div>
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <div class="footer-logo">
                            <img src="{{ asset('frontend/images/Logo.png') }}" alt="CSIRT PALI">
                            <h3>CSIRT PALI</h3>
                        </div>
                        <p class="footer-description">
                            Computer Security Incident Response Team for PALI, dedicated to protecting digital infrastructure across the Americas.
                        </p>
                    </div>
                    <div class="footer-section">
                        <h4>Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('profile') }}">About</a></li>
                            <li><a href="{{ route('services') }}">Services</a></li>
                            <li><a href="{{ route('news.index') }}">News</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Contact Info</h4>
                        <div class="contact-info">
                            <p><i class="fas fa-envelope"></i> info@csirtpali.org</p>
                            <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                            <p><i class="fas fa-map-marker-alt"></i> Americas Region</p>
                        </div>
                    </div>
                    <div class="footer-section">
                        <h4>Follow Us</h4>
                        <div class="social-links">
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                            <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} CSIRT PALI. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script>
        // Share functionality
        function shareArticle() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ isset($news) ? addslashes($news->title) : "" }}',
                    text: '{{ isset($news) && $news->excerpt ? addslashes($news->excerpt) : "" }}',
                    url: window.location.href
                });
            } else {
                // Fallback to copying URL
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Article URL copied to clipboard!');
                });
            }
        }

        // Newsletter form
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('.newsletter-input').value;
            const btn = this.querySelector('.newsletter-btn');
            
            // Simulate loading
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Subscribed!';
                this.reset();
                
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-paper-plane"></i> Subscribe';
                    btn.disabled = false;
                }, 3000);
            }, 2000);
        });
    </script>
</body>
</html>