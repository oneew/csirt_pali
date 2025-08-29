<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSIRT PALI - Cybersecurity Incident Response Team</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
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
                                <a href="{{ route('home') }}" class="nav-link active">Home</a>
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
                                <a href="{{ route('news.index') }}" class="nav-link">News</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-background">
                <div class="hero-shapes">
                    <div class="hero-shape-1"></div>
                    <div class="hero-shape-2"></div>
                    <div class="hero-shape-3"></div>
                </div>
            </div>
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            <span class="hero-title-main">CSIRT</span>
                            <span class="hero-title-sub">PALI</span>
                        </h1>
                        <p class="hero-subtitle">
                            Computer Security Incident Response Team for PALI
                        </p>
                        <p class="hero-description">
                            Protecting digital infrastructure through coordinated cybersecurity incident response, 
                            threat intelligence sharing, and capacity building across the Americas.
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('news.index') }}" class="btn btn-primary">
                                <i class="fas fa-shield-alt"></i>
                                Latest Alerts
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-secondary">
                                <i class="fas fa-envelope"></i>
                                Report Incident
                            </a>
                        </div>
                    </div>
                    <div class="hero-stats">
                        @if(isset($stats))
                        <div class="hero-stat">
                            <div class="hero-stat-number" data-count="{{ $stats['total_incidents'] ?? 0 }}">{{ $stats['total_incidents'] ?? 0 }}</div>
                            <div class="hero-stat-label">Total Incidents</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number" data-count="{{ $stats['resolved_incidents'] ?? 0 }}">{{ $stats['resolved_incidents'] ?? 0 }}</div>
                            <div class="hero-stat-label">Resolved Cases</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number" data-count="{{ $stats['active_threats'] ?? 0 }}">{{ $stats['active_threats'] ?? 0 }}</div>
                            <div class="hero-stat-label">Active Threats</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number" data-count="{{ $stats['total_members'] ?? 0 }}">{{ $stats['total_members'] ?? 0 }}</div>
                            <div class="hero-stat-label">Team Members</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @if(isset($featuredNews) && $featuredNews->count() > 0)
        <!-- Latest News Section -->
        <section class="latest-news">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Latest Security Updates</h2>
                    <p class="section-subtitle">Stay informed with the latest cybersecurity news and threat intelligence</p>
                </div>
                <div class="news-grid">
                    @foreach($featuredNews as $news)
                    <article class="news-card">
                        <div class="news-image">
                            <img src="{{ $news->featured_image ? asset('storage/' . $news->featured_image) : asset('frontend/images/bg2.png') }}" alt="{{ $news->title }}">
                            <div class="news-category {{ $news->category }}">
                                {{ ucfirst(str_replace('_', ' ', $news->category)) }}
                            </div>
                        </div>
                        <div class="news-content">
                            <h3 class="news-title">
                                <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                            </h3>
                            <p class="news-excerpt">
                                {{ Str::limit($news->excerpt ?? strip_tags($news->content), 120) }}
                            </p>
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="fas fa-calendar"></i>
                                    {{ $news->published_at ? $news->published_at->format('M j, Y') : $news->created_at->format('M j, Y') }}
                                </span>
                                @if($news->priority && $news->priority !== 'low')
                                <span class="news-priority {{ $news->priority }}">
                                    <i class="fas fa-flag"></i>
                                    {{ ucfirst($news->priority) }}
                                </span>
                                @endif
                            </div>
                            <a href="{{ route('news.show', $news->slug) }}" class="read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
                <div class="section-footer">
                    <a href="{{ route('news.index') }}" class="btn btn-outline">
                        <i class="fas fa-newspaper"></i>
                        View All News
                    </a>
                </div>
            </div>
        </section>
        @endif

        @if(isset($featuredServices) && $featuredServices->count() > 0)
        <!-- Services Section -->
        <section class="services">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-subtitle">Comprehensive cybersecurity solutions for organizations across the Americas</p>
                </div>
                <div class="services-grid">
                    @foreach($featuredServices as $service)
                    <div class="service-card">
                        <div class="service-icon">
                            @if($service->icon)
                                <i class="{{ $service->icon }}"></i>
                            @else
                                <i class="fas fa-shield-alt"></i>
                            @endif
                        </div>
                        <h3 class="service-title">{{ $service->name }}</h3>
                        <p class="service-description">{{ Str::limit($service->description, 100) }}</p>
                        <a href="{{ route('services.show', $service->slug) }}" class="service-link">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="section-footer">
                    <a href="{{ route('services') }}" class="btn btn-outline">
                        <i class="fas fa-cogs"></i>
                        View All Services
                    </a>
                </div>
            </div>
        </section>
        @endif

        @if(isset($recentGallery) && $recentGallery->count() > 0)
        <!-- Gallery Section -->
        <section class="gallery-preview">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Recent Activities</h2>
                    <p class="section-subtitle">Highlights from our cybersecurity events and collaborations</p>
                </div>
                <div class="gallery-grid">
                    @foreach($recentGallery as $item)
                    <div class="gallery-item">
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('frontend/images/bg2.png') }}" alt="{{ $item->title }}">
                        <div class="gallery-overlay">
                            <h4 class="gallery-title">{{ $item->title }}</h4>
                            <p class="gallery-date">{{ $item->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="section-footer">
                    <a href="{{ route('gallery') }}" class="btn btn-outline">
                        <i class="fas fa-images"></i>
                        View Gallery
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
        // Animated counter for hero stats
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 20);
        }

        // Initialize counters when hero section is visible
        const heroObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = document.querySelectorAll('.hero-stat-number');
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-count'));
                        animateCounter(counter, target);
                    });
                    heroObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (document.querySelector('.hero')) {
            heroObserver.observe(document.querySelector('.hero'));
        }
    </script>
</body>
</html>