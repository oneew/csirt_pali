<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - CSIRT PALI</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/services.css') }}">
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
                            <a href="{{ route('services') }}" class="nav-link active">Services</a>
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
    <section class="services-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Our Services</h1>
                <p class="hero-subtitle">
                    Comprehensive cybersecurity solutions designed to protect and strengthen digital infrastructure across the Americas
                </p>
            </div>
        </div>
    </section>

    @if(isset($featuredServices) && $featuredServices->count() > 0)
    <!-- Featured Services -->
    <section class="featured-services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Services</h2>
                <p class="section-subtitle">Our core cybersecurity services that provide essential protection and support</p>
            </div>
            <div class="services-grid featured">
                @foreach($featuredServices as $service)
                <div class="service-card featured-card">
                    <div class="service-icon">
                        @if($service->icon)
                            <i class="{{ $service->icon }}"></i>
                        @else
                            <i class="fas fa-shield-alt"></i>
                        @endif
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">{{ $service->name }}</h3>
                        <p class="service-description">{{ $service->description }}</p>
                        @if($service->features && count($service->features) > 0)
                        <ul class="service-features">
                            @foreach(array_slice($service->features, 0, 3) as $feature)
                            <li><i class="fas fa-check"></i> {{ $feature }}</li>
                            @endforeach
                        </ul>
                        @endif
                        <div class="service-footer">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-primary">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- All Services -->
    <section class="all-services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    @if(isset($featuredServices) && $featuredServices->count() > 0)
                        All Services
                    @else
                        Our Services
                    @endif
                </h2>
                <p class="section-subtitle">Complete range of cybersecurity solutions tailored to your organization's needs</p>
            </div>

            @if(isset($services) && $services->count() > 0)
            <div class="services-grid">
                @foreach($services as $service)
                <div class="service-card">
                    <div class="service-icon">
                        @if($service->icon)
                            <i class="{{ $service->icon }}"></i>
                        @else
                            <i class="fas fa-shield-alt"></i>
                        @endif
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">{{ $service->name }}</h3>
                        <p class="service-description">{{ Str::limit($service->description, 150) }}</p>
                        @if($service->price && $service->price > 0)
                        <div class="service-price">
                            <span class="price-label">Starting from</span>
                            <span class="price-amount">${{ number_format($service->price) }}</span>
                        </div>
                        @endif
                        <div class="service-footer">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline">
                                View Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Placeholder services when no backend data exists -->
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Incident Response</h3>
                        <p class="service-description">
                            24/7 rapid response to cybersecurity incidents with expert analysis, containment, and recovery strategies.
                        </p>
                        <div class="service-footer">
                            <a href="{{ route('contact') }}" class="btn btn-outline">
                                Contact Us <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Threat Intelligence</h3>
                        <p class="service-description">
                            Comprehensive threat intelligence services providing actionable insights on emerging cybersecurity threats.
                        </p>
                        <div class="service-footer">
                            <a href="{{ route('contact') }}" class="btn btn-outline">
                                Contact Us <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Vulnerability Assessment</h3>
                        <p class="service-description">
                            Systematic identification and assessment of security vulnerabilities in your digital infrastructure.
                        </p>
                        <div class="service-footer">
                            <a href="{{ route('contact') }}" class="btn btn-outline">
                                Contact Us <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Security Training</h3>
                        <p class="service-description">
                            Comprehensive cybersecurity training programs for teams and organizations across all skill levels.
                        </p>
                        <div class="service-footer">
                            <a href="{{ route('contact') }}" class="btn btn-outline">
                                Contact Us <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Security Consulting</h3>
                        <p class="service-description">
                            Expert cybersecurity consulting services to help organizations develop robust security strategies.
                        </p>
                        <div class="service-footer">
                            <a href="{{ route('contact') }}" class="btn btn-outline">
                                Contact Us <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Collaboration Platform</h3>
                        <p class="service-description">
                            Secure platform for CSIRT teams to collaborate, share intelligence, and coordinate response efforts.
                        </p>
                        <div class="service-footer">
                            <a href="{{ route('contact') }}" class="btn btn-outline">
                                Contact Us <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($services) && $services->hasPages())
            <!-- Pagination -->
            <div class="pagination">
                @if($services->onFirstPage())
                    <button class="pagination-btn prev" disabled>
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </button>
                @else
                    <a href="{{ $services->previousPageUrl() }}" class="pagination-btn prev">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif
                
                <div class="pagination-numbers">
                    @foreach($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                        @if($page == $services->currentPage())
                            <button class="pagination-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                @if($services->hasMorePages())
                    <a href="{{ $services->nextPageUrl() }}" class="pagination-btn next">
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
        </div>
    </section>

    <!-- Call to Action -->
    <section class="services-cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Need Cybersecurity Support?</h2>
                <p class="cta-description">
                    Get in touch with our expert team to discuss your cybersecurity needs and find the right solution for your organization.
                </p>
                <div class="cta-actions">
                    <a href="{{ route('contact') }}" class="btn btn-primary">
                        <i class="fas fa-phone"></i>
                        Contact Us
                    </a>
                    <a href="{{ route('news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-newspaper"></i>
                        Latest Updates
                    </a>
                </div>
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
                    <h4>Services</h4>
                    <ul class="footer-links">
                        <li><a href="#incident-response">Incident Response</a></li>
                        <li><a href="#threat-intelligence">Threat Intelligence</a></li>
                        <li><a href="#vulnerability-assessment">Vulnerability Assessment</a></li>
                        <li><a href="#security-training">Security Training</a></li>
                        <li><a href="#security-consulting">Security Consulting</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('profile') }}">About</a></li>
                        <li><a href="{{ route('news.index') }}">News</a></li>
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
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} CSIRT PALI. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script>
        // Animate service cards on scroll
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

        document.querySelectorAll('.service-card').forEach(card => {
            observer.observe(card);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>