<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - CSIRT PALI</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/contact.css') }}">
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
                            <a href="{{ route('news.index') }}" class="nav-link">News</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact') }}" class="nav-link active">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="contact-hero-content">
            <h1 class="hero-title">Contact Us</h1>
            <p class="hero-subtitle">
                Get in touch with our cybersecurity experts and join the CSIRT network
            </p>
        </div>
    </section>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="container">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <div class="container">
            <i class="fas fa-exclamation-triangle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Address</h3>
                        <p>CSIRT PALI Headquarters<br>
                        Cybersecurity Center<br>
                        Americas Region</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Phone</h3>
                        <p><a href="tel:+15551234567">+1 (555) 123-4567</a></p>
                        <p>Mon - Fri: 9:00 AM - 6:00 PM</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Email</h3>
                        <p><a href="mailto:info@csirtpali.org">info@csirtpali.org</a></p>
                        <p><a href="mailto:support@csirtpali.org">support@csirtpali.org</a></p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Website</h3>
                        <p><a href="https://csirtpali.org" target="_blank">www.csirtpali.org</a></p>
                        <p>Visit our main portal</p>
                    </div>
                </div>

                <div class="emergency-contact">
                    <h3><i class="fas fa-exclamation-triangle"></i> Emergency Contact</h3>
                    <p><strong>24/7 Incident Response</strong></p>
                    <p><a href="tel:+15551234568" style="color: #fff; text-decoration: none;">+1 (555) 123-4568</a></p>
                    <p><a href="mailto:emergency@csirtpali.org" style="color: #fff; text-decoration: none;">emergency@csirtpali.org</a></p>
                </div>
            </div>

            <div class="contact-form">
                <h2>Send us a Message</h2>
                <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="organization">Organization</label>
                        <input type="text" id="organization" name="organization" class="form-control" value="{{ old('organization') }}">
                        @error('organization')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" class="form-control" required>
                            <option value="">Select a subject</option>
                            <option value="incident" {{ old('subject') == 'incident' ? 'selected' : '' }}>Incident Report</option>
                            <option value="membership" {{ old('subject') == 'membership' ? 'selected' : '' }}>Membership Inquiry</option>
                            <option value="training" {{ old('subject') == 'training' ? 'selected' : '' }}>Training Request</option>
                            <option value="collaboration" {{ old('subject') == 'collaboration' ? 'selected' : '' }}>Collaboration Proposal</option>
                            <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Technical Support</option>
                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('subject')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" class="form-control textarea" rows="5" required placeholder="Please describe your inquiry in detail...">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="map-container">
            <h2 style="text-align: center; margin-bottom: 2rem; font-size: 2rem; color: #1e293b;">Our Location</h2>
            <div class="map-placeholder">
                <div class="map-content">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>CSIRT PALI</h3>
                    <p>Americas Region</p>
                    <p>Cybersecurity Operations Center</p>
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
                        <li><a href="{{ route('profile') }}">About</a></li>
                        <li><a href="{{ route('services') }}">Services</a></li>
                        <li><a href="{{ route('news.index') }}">News</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
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
        // Form field animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    </script>
</body>
</html>