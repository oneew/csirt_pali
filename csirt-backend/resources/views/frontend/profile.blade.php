<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - CSIRT PALI</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
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
                            <a href="{{ route('profile') }}" class="nav-link active">Profile</a>
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
    <section class="profile-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    {{ isset($organizationInfo) && $organizationInfo['name'] ? $organizationInfo['name'] : 'CSIRT PALI' }}
                </h1>
                <p class="hero-subtitle">
                    Computer Security Incident Response Team for PALI
                </p>
                <p class="hero-description">
                    {{ isset($organizationInfo) && $organizationInfo['description'] ? $organizationInfo['description'] : 'Protecting digital infrastructure through coordinated cybersecurity incident response, threat intelligence sharing, and capacity building across the Americas.' }}
                </p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="section-title">About Our Organization</h2>
                    @if(isset($organizationInfo) && $organizationInfo['mission'])
                    <div class="mission-vision">
                        <div class="mission">
                            <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
                            <p>{{ $organizationInfo['mission'] }}</p>
                        </div>
                        @if($organizationInfo['vision'])
                        <div class="vision">
                            <h3><i class="fas fa-eye"></i> Our Vision</h3>
                            <p>{{ $organizationInfo['vision'] }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="mission-vision">
                        <div class="mission">
                            <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
                            <p>
                                To strengthen cybersecurity capabilities across the Americas by providing coordinated incident response, 
                                threat intelligence sharing, and capacity building services that enhance the collective defense posture 
                                of member organizations.
                            </p>
                        </div>
                        <div class="vision">
                            <h3><i class="fas fa-eye"></i> Our Vision</h3>
                            <p>
                                A secure digital ecosystem across the Americas where organizations can operate with confidence, 
                                supported by robust cybersecurity incident response capabilities and collaborative threat intelligence sharing.
                            </p>
                        </div>
                    </div>
                    @endif

                    <div class="about-details">
                        <h3>What We Do</h3>
                        <div class="capabilities-grid">
                            <div class="capability">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h4>Incident Response</h4>
                                <p>24/7 rapid response to cybersecurity incidents with expert analysis and remediation support.</p>
                            </div>
                            <div class="capability">
                                <i class="fas fa-shield-alt"></i>
                                <h4>Threat Intelligence</h4>
                                <p>Comprehensive threat intelligence services providing actionable insights on emerging threats.</p>
                            </div>
                            <div class="capability">
                                <i class="fas fa-users"></i>
                                <h4>Collaboration</h4>
                                <p>Facilitating cooperation and information sharing among CSIRT teams across the region.</p>
                            </div>
                            <div class="capability">
                                <i class="fas fa-graduation-cap"></i>
                                <h4>Capacity Building</h4>
                                <p>Training and development programs to enhance cybersecurity skills and capabilities.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($teamMembers) && $teamMembers->count() > 0)
    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Team</h2>
                <p class="section-subtitle">Meet the experts dedicated to protecting digital infrastructure across the Americas</p>
            </div>
            <div class="team-grid">
                @foreach($teamMembers as $member)
                <div class="team-member">
                    <div class="member-photo">
                        @if($member->avatar)
                            <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->full_name }}">
                        @else
                            <div class="default-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="member-info">
                        <h3 class="member-name">{{ $member->full_name }}</h3>
                        <p class="member-role">{{ ucfirst($member->role) }}</p>
                        @if($member->bio)
                            <p class="member-bio">{{ Str::limit($member->bio, 120) }}</p>
                        @endif
                        @if($member->email)
                            <div class="member-contact">
                                <a href="mailto:{{ $member->email }}" class="contact-link">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($services) && $services->count() > 0)
    <!-- Services Overview -->
    <section class="services-overview">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Services</h2>
                <p class="section-subtitle">Comprehensive cybersecurity solutions for the Americas</p>
            </div>
            <div class="services-grid">
                @foreach($services->take(6) as $service)
                <div class="service-item">
                    <div class="service-icon">
                        @if($service->icon)
                            <i class="{{ $service->icon }}"></i>
                        @else
                            <i class="fas fa-shield-alt"></i>
                        @endif
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">{{ $service->name }}</h3>
                        <p class="service-description">{{ Str::limit($service->description, 100) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="section-footer">
                <a href="{{ route('services') }}" class="btn btn-primary">
                    <i class="fas fa-cogs"></i>
                    View All Services
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Information -->
    <section class="contact-info-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">Contact us for cybersecurity support and collaboration opportunities</p>
            </div>
            <div class="contact-grid">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-content">
                        <h3>Email</h3>
                        <p>{{ isset($organizationInfo) && $organizationInfo['email'] ? $organizationInfo['email'] : 'info@csirtpali.org' }}</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-content">
                        <h3>Phone</h3>
                        <p>{{ isset($organizationInfo) && $organizationInfo['phone'] ? $organizationInfo['phone'] : '+1 (555) 123-4567' }}</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-content">
                        <h3>Location</h3>
                        <p>{{ isset($organizationInfo) && $organizationInfo['address'] ? $organizationInfo['address'] : 'Americas Region' }}</p>
                    </div>
                </div>
            </div>
            <div class="section-footer">
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="50">50</div>
                    <div class="stat-label">Member Organizations</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="1000">1000</div>
                    <div class="stat-label">Incidents Handled</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="24">24</div>
                    <div class="stat-label">Countries Served</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="365">365</div>
                    <div class="stat-label">Days Active</div>
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
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('services') }}">Services</a></li>
                        <li><a href="{{ route('news.index') }}">News</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Services</h4>
                    <ul class="footer-links">
                        <li><a href="#incident-response">Incident Response</a></li>
                        <li><a href="#threat-intelligence">Threat Intelligence</a></li>
                        <li><a href="#vulnerability-assessment">Vulnerability Assessment</a></li>
                        <li><a href="#security-training">Security Training</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> {{ isset($organizationInfo) && $organizationInfo['email'] ? $organizationInfo['email'] : 'info@csirtpali.org' }}</p>
                        <p><i class="fas fa-phone"></i> {{ isset($organizationInfo) && $organizationInfo['phone'] ? $organizationInfo['phone'] : '+1 (555) 123-4567' }}</p>
                        <p><i class="fas fa-map-marker-alt"></i> {{ isset($organizationInfo) && $organizationInfo['address'] ? $organizationInfo['address'] : 'Americas Region' }}</p>
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
        // Animate statistics on scroll
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + '+';
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 20);
        }

        // Initialize counters when stats section is visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = document.querySelectorAll('.stat-number');
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-count'));
                        animateCounter(counter, target);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (document.querySelector('.stats-section')) {
            statsObserver.observe(document.querySelector('.stats-section'));
        }

        // Animate team members on scroll
        const teamObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-in');
                    }, index * 100);
                    teamObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.team-member').forEach(member => {
            teamObserver.observe(member);
        });
    </script>
</body>
</html>