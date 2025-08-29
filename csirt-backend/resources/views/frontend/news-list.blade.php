<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News - CSIRT PALI</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/news.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
</head>
<body>
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

    <!-- News Hero Section -->
    <section class="news-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    @if(isset($category) && $category)
                        {{ $categoryName ?? ucfirst(str_replace('_', ' ', $category)) }} News
                    @else
                        Latest News & Updates
                    @endif
                </h1>
                <p class="hero-subtitle">
                    Stay informed with the latest cybersecurity news, threat alerts, and industry updates
                </p>
            </div>
        </div>
    </section>

    <!-- News Content -->
    <section class="news-content">
        <div class="container">
            <div class="news-layout">
                <!-- Main Content -->
                <div class="news-main">
                    @if(isset($featuredNews) && $featuredNews->count() > 0)
                    <!-- Featured News -->
                    <div class="featured-news">
                        <h2 class="section-title">Featured Articles</h2>
                        <div class="featured-grid">
                            @foreach($featuredNews as $news)
                            <article class="featured-article">
                                <div class="article-image">
                                    <img src="{{ $news->featured_image ? asset('storage/' . $news->featured_image) : asset('frontend/images/bg2.png') }}" alt="{{ $news->title }}">
                                    <div class="article-category {{ $news->category }}">
                                        {{ ucfirst(str_replace('_', ' ', $news->category)) }}
                                    </div>
                                </div>
                                <div class="article-content">
                                    <h3 class="article-title">
                                        <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                                    </h3>
                                    <p class="article-excerpt">
                                        {{ Str::limit($news->excerpt ?? strip_tags($news->content), 120) }}
                                    </p>
                                    <div class="article-meta">
                                        <span class="article-date">
                                            <i class="fas fa-calendar"></i>
                                            {{ $news->published_at ? $news->published_at->format('M j, Y') : $news->created_at->format('M j, Y') }}
                                        </span>
                                        @if($news->priority && $news->priority !== 'low')
                                        <span class="article-priority {{ $news->priority }}">
                                            <i class="fas fa-flag"></i>
                                            {{ ucfirst($news->priority) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Recent News -->
                    <div class="recent-news">
                        <h2 class="section-title">
                            @if(isset($category) && $category)
                                {{ $categoryName ?? ucfirst(str_replace('_', ' ', $category)) }} Articles
                            @else
                                Recent Articles
                            @endif
                        </h2>

                        @if((isset($recentNews) && $recentNews->count() > 0) || (isset($news) && $news->count() > 0))
                        <div class="news-grid">
                            @php
                                $newsItems = isset($recentNews) ? $recentNews : (isset($news) ? $news : collect());
                            @endphp
                            
                            @foreach($newsItems as $newsItem)
                            <article class="news-card">
                                <div class="news-image">
                                    <img src="{{ $newsItem->featured_image ? asset('storage/' . $newsItem->featured_image) : asset('frontend/images/bg2.png') }}" alt="{{ $newsItem->title }}">
                                    <div class="news-category {{ $newsItem->category }}">
                                        {{ ucfirst(str_replace('_', ' ', $newsItem->category)) }}
                                    </div>
                                </div>
                                <div class="news-content">
                                    <h3 class="news-title">
                                        <a href="{{ route('news.show', $newsItem->slug) }}">{{ $newsItem->title }}</a>
                                    </h3>
                                    <p class="news-excerpt">
                                        {{ Str::limit($newsItem->excerpt ?? strip_tags($newsItem->content), 100) }}
                                    </p>
                                    <div class="news-meta">
                                        <span class="news-date">
                                            <i class="fas fa-calendar"></i>
                                            {{ $newsItem->published_at ? $newsItem->published_at->format('M j, Y') : $newsItem->created_at->format('M j, Y') }}
                                        </span>
                                        @if($newsItem->author)
                                        <span class="news-author">
                                            <i class="fas fa-user"></i>
                                            {{ $newsItem->author->full_name }}
                                        </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('news.show', $newsItem->slug) }}" class="read-more">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                            @endforeach
                        </div>

                        @if(isset($recentNews) && $recentNews->hasPages())
                        <!-- Pagination -->
                        <div class="pagination">
                            @if($recentNews->onFirstPage())
                                <button class="pagination-btn prev" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </button>
                            @else
                                <a href="{{ $recentNews->previousPageUrl() }}" class="pagination-btn prev">
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </a>
                            @endif
                            
                            <div class="pagination-numbers">
                                @foreach($recentNews->getUrlRange(1, $recentNews->lastPage()) as $page => $url)
                                    @if($page == $recentNews->currentPage())
                                        <button class="pagination-number active">{{ $page }}</button>
                                    @else
                                        <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>
                            
                            @if($recentNews->hasMorePages())
                                <a href="{{ $recentNews->nextPageUrl() }}" class="pagination-btn next">
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <button class="pagination-btn next" disabled>
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                        @endif

                        @if(isset($news) && $news->hasPages())
                        <!-- Pagination for category news -->
                        <div class="pagination">
                            @if($news->onFirstPage())
                                <button class="pagination-btn prev" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </button>
                            @else
                                <a href="{{ $news->previousPageUrl() }}" class="pagination-btn prev">
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </a>
                            @endif
                            
                            <div class="pagination-numbers">
                                @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                                    @if($page == $news->currentPage())
                                        <button class="pagination-number active">{{ $page }}</button>
                                    @else
                                        <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>
                            
                            @if($news->hasMorePages())
                                <a href="{{ $news->nextPageUrl() }}" class="pagination-btn next">
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <button class="pagination-btn next" disabled>
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                        @endif
                        @else
                        <!-- No News Found -->
                        <div class="no-news">
                            <i class="fas fa-newspaper"></i>
                            <h3>No Articles Found</h3>
                            <p>
                                @if(isset($category) && $category)
                                    No articles found in the {{ $categoryName ?? $category }} category.
                                @else
                                    No articles have been published yet. Check back later for updates.
                                @endif
                            </p>
                            @if(isset($category) && $category)
                                <a href="{{ route('news.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i>
                                    View All News
                                </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="news-sidebar">
                    @if(isset($popularCategories) && $popularCategories->count() > 0)
                    <!-- Categories -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Categories</h3>
                        <ul class="category-list">
                            <li>
                                <a href="{{ route('news.index') }}" class="{{ !isset($category) ? 'active' : '' }}">
                                    <i class="fas fa-newspaper"></i>
                                    All News
                                </a>
                            </li>
                            @foreach($popularCategories as $cat)
                            <li>
                                <a href="{{ route('news.category', $cat->category) }}" class="{{ isset($category) && $category == $cat->category ? 'active' : '' }}">
                                    <i class="fas fa-tag"></i>
                                    {{ ucfirst(str_replace('_', ' ', $cat->category)) }}
                                    <span class="count">({{ $cat->count }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(isset($sidebarNews) && $sidebarNews->count() > 0)
                    <!-- Recent Articles -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Recent Articles</h3>
                        <div class="recent-articles">
                            @foreach($sidebarNews as $recent)
                            <article class="recent-article">
                                <div class="recent-thumbnail">
                                    <img src="{{ $recent->featured_image ? asset('storage/' . $recent->featured_image) : asset('frontend/images/bg2.png') }}" alt="{{ $recent->title }}">
                                </div>
                                <div class="recent-content">
                                    <h4 class="recent-title">
                                        <a href="{{ route('news.show', $recent->slug) }}">{{ Str::limit($recent->title, 60) }}</a>
                                    </h4>
                                    <span class="recent-date">{{ $recent->published_at ? $recent->published_at->format('M j, Y') : $recent->created_at->format('M j, Y') }}</span>
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
                            <a href="{{ route('services') }}" class="quick-action">
                                <i class="fas fa-shield-alt"></i>
                                Our Services
                            </a>
                            <a href="{{ route('profile') }}" class="quick-action">
                                <i class="fas fa-info-circle"></i>
                                About CSIRT PALI
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
    </section>

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
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
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

    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script>
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

        // Animate news cards on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-in');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.news-card, .featured-article').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>
</html>