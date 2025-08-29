<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CSIRT PALI</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
</head>
<body>
    <header class="header">
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
                            <a href="{{ route('dashboard') }}" class="nav-link active">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('news.index') }}" class="nav-link">News</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                        </li>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'operator')
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Panel</a>
                        </li>
                        @endif
                    </ul>
                    <div class="nav-user">
                        <div class="user-dropdown">
                            <button class="user-toggle">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->full_name }}" class="user-avatar">
                                @else
                                    <div class="user-avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <span class="user-name">{{ Auth::user()->first_name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-menu">
                                <a href="#" class="user-menu-item">
                                    <i class="fas fa-user"></i>
                                    Profile
                                </a>
                                <a href="#" class="user-menu-item">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                                <div class="user-menu-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                    @csrf
                                    <button type="submit" class="user-menu-item logout-btn">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Dashboard Content -->
    <main class="dashboard-content">
        <div class="container">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-content">
                    <h1 class="welcome-title">Welcome back, {{ Auth::user()->first_name }}!</h1>
                    <p class="welcome-subtitle">Here's an overview of your CSIRT activities and recent updates.</p>
                </div>
                <div class="welcome-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->full_name }}" class="avatar-large">
                    @else
                        <div class="avatar-large-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Cards -->
            @if(isset($userStats))
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon incidents">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $userStats['incidents_reported'] ?? 0 }}</div>
                        <div class="stat-label">Incidents Reported</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon assigned">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $userStats['incidents_assigned'] ?? 0 }}</div>
                        <div class="stat-label">Assigned to Me</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon resolved">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $userStats['incidents_resolved'] ?? 0 }}</div>
                        <div class="stat-label">Resolved Cases</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon notifications">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $userStats['unread_notifications'] ?? 0 }}</div>
                        <div class="stat-label">Unread Notifications</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Dashboard Content Grid -->
            <div class="dashboard-grid">
                <!-- Recent Incidents -->
                @if(isset($reportedIncidents) && $reportedIncidents->count() > 0)
                <div class="dashboard-widget">
                    <div class="widget-header">
                        <h2 class="widget-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            My Reported Incidents
                        </h2>
                        <a href="#" class="widget-action">View All</a>
                    </div>
                    <div class="widget-content">
                        <div class="incidents-list">
                            @foreach($reportedIncidents as $incident)
                            <div class="incident-item">
                                <div class="incident-info">
                                    <h4 class="incident-title">{{ $incident->title }}</h4>
                                    <p class="incident-meta">
                                        <span class="incident-id">#{{ $incident->id }}</span>
                                        <span class="incident-date">{{ $incident->detected_at->format('M j, Y') }}</span>
                                    </p>
                                </div>
                                <div class="incident-status">
                                    <span class="status-badge {{ $incident->status }}">{{ ucfirst($incident->status) }}</span>
                                    <span class="severity-badge {{ $incident->severity }}">{{ ucfirst($incident->severity) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($assignedIncidents) && $assignedIncidents->count() > 0)
                <!-- Assigned Incidents -->
                <div class="dashboard-widget">
                    <div class="widget-header">
                        <h2 class="widget-title">
                            <i class="fas fa-tasks"></i>
                            Assigned to Me
                        </h2>
                        <a href="#" class="widget-action">View All</a>
                    </div>
                    <div class="widget-content">
                        <div class="incidents-list">
                            @foreach($assignedIncidents as $incident)
                            <div class="incident-item">
                                <div class="incident-info">
                                    <h4 class="incident-title">{{ $incident->title }}</h4>
                                    <p class="incident-meta">
                                        <span class="incident-id">#{{ $incident->id }}</span>
                                        <span class="incident-reporter">by {{ $incident->reporter->full_name ?? 'Unknown' }}</span>
                                    </p>
                                </div>
                                <div class="incident-status">
                                    <span class="status-badge {{ $incident->status }}">{{ ucfirst($incident->status) }}</span>
                                    <span class="severity-badge {{ $incident->severity }}">{{ ucfirst($incident->severity) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($recentNews) && $recentNews->count() > 0)
                <!-- Recent News -->
                <div class="dashboard-widget">
                    <div class="widget-header">
                        <h2 class="widget-title">
                            <i class="fas fa-newspaper"></i>
                            Recent News
                        </h2>
                        <a href="{{ route('news.index') }}" class="widget-action">View All</a>
                    </div>
                    <div class="widget-content">
                        <div class="news-list">
                            @foreach($recentNews as $news)
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="{{ $news->featured_image ? asset('storage/' . $news->featured_image) : asset('frontend/images/bg2.png') }}" alt="{{ $news->title }}">
                                </div>
                                <div class="news-content">
                                    <h4 class="news-title">
                                        <a href="{{ route('news.show', $news->slug) }}">{{ Str::limit($news->title, 60) }}</a>
                                    </h4>
                                    <p class="news-excerpt">{{ Str::limit($news->excerpt ?? strip_tags($news->content), 80) }}</p>
                                    <div class="news-meta">
                                        <span class="news-category {{ $news->category }}">{{ ucfirst(str_replace('_', ' ', $news->category)) }}</span>
                                        <span class="news-date">{{ $news->published_at ? $news->published_at->format('M j') : $news->created_at->format('M j') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="dashboard-widget">
                    <div class="widget-header">
                        <h2 class="widget-title">
                            <i class="fas fa-lightning-bolt"></i>
                            Quick Actions
                        </h2>
                    </div>
                    <div class="widget-content">
                        <div class="quick-actions">
                            <a href="{{ route('contact') }}" class="quick-action">
                                <div class="action-icon report">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Report Incident</h4>
                                    <p>Report a security incident</p>
                                </div>
                            </a>
                            <a href="{{ route('news.index') }}" class="quick-action">
                                <div class="action-icon news">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Latest News</h4>
                                    <p>View security updates</p>
                                </div>
                            </a>
                            <a href="{{ route('services') }}" class="quick-action">
                                <div class="action-icon services">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Our Services</h4>
                                    <p>Explore CSIRT services</p>
                                </div>
                            </a>
                            <a href="{{ route('contact') }}" class="quick-action">
                                <div class="action-icon support">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Get Support</h4>
                                    <p>Contact our team</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} CSIRT PALI. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script>
        // User dropdown functionality
        document.querySelector('.user-toggle').addEventListener('click', function() {
            document.querySelector('.user-menu').classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown')) {
                document.querySelector('.user-menu').classList.remove('active');
            }
        });

        // Animate widgets on scroll
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

        document.querySelectorAll('.dashboard-widget, .stat-card').forEach(widget => {
            observer.observe(widget);
        });

        // Animate stat numbers
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
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

        // Initialize counters when stats are visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = document.querySelectorAll('.stat-number');
                    counters.forEach(counter => {
                        const target = parseInt(counter.textContent);
                        animateCounter(counter, target);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (document.querySelector('.stats-grid')) {
            statsObserver.observe(document.querySelector('.stats-grid'));
        }
    </script>
</body>
</html>