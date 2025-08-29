<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name ?? 'Service' }} - CSIRT PALI</title>
    <meta name="description" content="{{ $service->description ?? 'CSIRT PALI Service' }}">
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

    <!-- Breadcrumb -->
    <section class="breadcrumb">
        <div class="container">
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="{{ route('services') }}" class="breadcrumb-link">Services</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">{{ Str::limit($service->name ?? 'Service', 50) }}</span>
            </nav>
        </div>
    </section>

    <!-- Service Detail Content -->
    <article class="service-detail">
        <div class="container">
            <div class="service-content">
                <div class="service-main">
                    <!-- Service Header -->
                    <header class="service-header">
                        <div class="service-icon-large">
                            @if($service->icon)
                                <i class="{{ $service->icon }}"></i>
                            @else
                                <i class="fas fa-shield-alt"></i>
                            @endif
                        </div>
                        <div class="service-header-content">
                            <div class="service-meta">
                                <span class="service-category">{{ ucfirst(str_replace('_', ' ', $service->category)) }}</span>
                                @if($service->is_featured)
                                <span class="service-featured">
                                    <i class="fas fa-star"></i>
                                    Featured Service
                                </span>
                                @endif
                            </div>
                            <h1 class="service-title">{{ $service->name }}</h1>
                            <p class="service-description">{{ $service->description }}</p>
                        </div>
                    </header>

                    <!-- Service Body -->
                    <div class="service-body">
                        <div class="service-content-text">
                            {!! nl2br(e($service->content)) !!}
                        </div>

                        @if($service->features && count($service->features) > 0)
                        <!-- Service Features -->
                        <div class="service-features">
                            <h2 class="features-title">Key Features</h2>
                            <ul class="features-list">
                                @foreach($service->features as $feature)
                                <li class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Service Details -->
                        <div class="service-details">
                            <h2 class="details-title">Service Information</h2>
                            <div class="details-grid">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-tag"></i>
                                        Category
                                    </div>
                                    <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $service->category)) }}</div>
                                </div>
                                @if($service->contact_email)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-envelope"></i>
                                        Contact Email
                                    </div>
                                    <div class="detail-value">
                                        <a href="mailto:{{ $service->contact_email }}">{{ $service->contact_email }}</a>
                                    </div>
                                </div>
                                @endif
                                @if($service->contact_phone)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-phone"></i>
                                        Contact Phone
                                    </div>
                                    <div class="detail-value">
                                        <a href="tel:{{ $service->contact_phone }}">{{ $service->contact_phone }}</a>
                                    </div>
                                </div>
                                @endif
                                @if($service->price && $service->price > 0)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-dollar-sign"></i>
                                        Starting Price
                                    </div>
                                    <div class="detail-value">${{ number_format($service->price) }}</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Call to Action -->
                        <div class="service-cta">
                            <h2 class="cta-title">Need This Service?</h2>
                            <p class="cta-description">
                                Get in touch with our expert team to discuss your requirements and learn how we can help protect your organization.
                            </p>
                            <div class="cta-actions">
                                <a href="{{ route('contact') }}" class="btn btn-primary">
                                    <i class="fas fa-envelope"></i>
                                    Contact Us
                                </a>
                                @if($service->contact_phone)
                                <a href="tel:{{ $service->contact_phone }}" class="btn btn-secondary">
                                    <i class="fas fa-phone"></i>
                                    Call {{ $service->contact_phone }}
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Service Actions -->
                        <div class="service-actions">
                            <button class="action-btn share-btn" onclick="shareService()">
                                <i class="fas fa-share-alt"></i>
                                Share Service
                            </button>
                            <button class="action-btn print-btn" onclick="window.print()">
                                <i class="fas fa-print"></i>
                                Print Details
                            </button>
                            <a href="{{ route('services') }}" class="action-btn back-btn">
                                <i class="fas fa-arrow-left"></i>
                                Back to Services
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="service-sidebar">
                    @if(isset($relatedServices) && $relatedServices->count() > 0)
                    <!-- Related Services -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Related Services</h3>
                        <div class="related-services">
                            @foreach($relatedServices as $related)
                            <div class="related-service">
                                <div class="related-icon">
                                    @if($related->icon)
                                        <i class="{{ $related->icon }}"></i>
                                    @else
                                        <i class="fas fa-shield-alt"></i>
                                    @endif
                                </div>
                                <div class="related-content">
                                    <h4 class="related-title">
                                        <a href="{{ route('services.show', $related->slug) }}">{{ $related->name }}</a>
                                    </h4>
                                    <p class="related-description">{{ Str::limit($related->description, 80) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quick Contact -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Quick Contact</h3>
                        <div class="quick-contact">
                            <p>Need immediate assistance with this service?</p>
                            <div class="contact-options">
                                <a href="{{ route('contact') }}" class="contact-option">
                                    <i class="fas fa-envelope"></i>
                                    Send Message
                                </a>
                                <a href="tel:+15551234567" class="contact-option">
                                    <i class="fas fa-phone"></i>
                                    Call Us
                                </a>
                                <a href="mailto:emergency@csirtpali.org" class="contact-option emergency">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Emergency
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Service Categories -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Service Categories</h3>
                        <ul class="category-list">
                            <li>
                                <a href="{{ route('services') }}?category=incident_response" class="{{ $service->category == 'incident_response' ? 'active' : '' }}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Incident Response
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services') }}?category=threat_intelligence" class="{{ $service->category == 'threat_intelligence' ? 'active' : '' }}">
                                    <i class="fas fa-shield-alt"></i>
                                    Threat Intelligence
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services') }}?category=training" class="{{ $service->category == 'training' ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap"></i>
                                    Training
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services') }}?category=consultation" class="{{ $service->category == 'consultation' ? 'active' : '' }}">
                                    <i class="fas fa-chart-line"></i>
                                    Consultation
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services') }}?category=assessment" class="{{ $service->category == 'assessment' ? 'active' : '' }}">
                                    <i class="fas fa-search"></i>
                                    Assessment
                                </a>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </article>

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
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('news.index') }}">News</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Services</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('services') }}?category=incident_response">Incident Response</a></li>
                        <li><a href="{{ route('services') }}?category=threat_intelligence">Threat Intelligence</a></li>
                        <li><a href="{{ route('services') }}?category=training">Training</a></li>
                        <li><a href="{{ route('services') }}?category=consultation">Consultation</a></li>
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
        // Share functionality
        function shareService() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($service->name) }}',
                    text: '{{ addslashes($service->description) }}',
                    url: window.location.href
                });
            } else {
                // Fallback to copying URL
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Service URL copied to clipboard!');
                });
            }
        }

        // Animate service elements on scroll
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

        document.querySelectorAll('.service-features, .service-details, .related-service').forEach(element => {
            observer.observe(element);
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

        // Feature list animation
        document.querySelectorAll('.feature-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 100);
        });
    </script>
</body>
</html>