<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gallery->title ?? 'Gallery Item' }} - CSIRT PALI</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/gallery.css') }}">
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
                            <a href="{{ route('gallery') }}" class="nav-link active">Gallery</a>
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
                <a href="{{ route('gallery') }}" class="breadcrumb-link">Gallery</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">{{ Str::limit($gallery->title ?? 'Gallery Item', 50) }}</span>
            </nav>
        </div>
    </section>

    <!-- Gallery Item Content -->
    <section class="gallery-item-detail">
        <div class="container">
            <div class="gallery-item-content">
                <div class="gallery-item-main">
                    <!-- Image Display -->
                    <div class="gallery-image-container">
                        <img src="{{ $gallery->image_path ? asset('storage/' . $gallery->image_path) : asset('frontend/images/bg2.png') }}" 
                             alt="{{ $gallery->title }}" 
                             class="gallery-image-main" 
                             id="mainImage">
                        
                        <!-- Image Actions -->
                        <div class="image-actions">
                            <button class="action-btn" onclick="openFullscreen()" title="View Fullscreen">
                                <i class="fas fa-expand"></i>
                            </button>
                            <button class="action-btn" onclick="downloadImage()" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="action-btn" onclick="shareImage()" title="Share">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Item Information -->
                    <div class="gallery-item-info">
                        <div class="item-header">
                            <h1 class="item-title">{{ $gallery->title }}</h1>
                            @if($gallery->category)
                            <span class="item-category">{{ ucfirst($gallery->category) }}</span>
                            @endif
                        </div>

                        @if($gallery->description)
                        <div class="item-description">
                            <p>{{ $gallery->description }}</p>
                        </div>
                        @endif

                        <div class="item-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $gallery->created_at->format('F j, Y') }}</span>
                            </div>
                            @if($gallery->uploader)
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>{{ $gallery->uploader->full_name }}</span>
                            </div>
                            @endif
                            @if($gallery->metadata && isset($gallery->metadata['file_size']))
                            <div class="meta-item">
                                <i class="fas fa-file"></i>
                                <span>{{ $gallery->metadata['file_size'] ?? 'Unknown size' }}</span>
                            </div>
                            @endif
                            @if($gallery->metadata && isset($gallery->metadata['dimensions']))
                            <div class="meta-item">
                                <i class="fas fa-expand-arrows-alt"></i>
                                <span>{{ $gallery->metadata['dimensions'] ?? 'Unknown dimensions' }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Navigation -->
                        <div class="item-navigation">
                            <a href="{{ route('gallery') }}" class="nav-btn back-btn">
                                <i class="fas fa-arrow-left"></i>
                                Back to Gallery
                            </a>
                            <div class="nav-arrows">
                                <button class="nav-btn prev-btn" onclick="navigateItem('prev')" title="Previous Item">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="nav-btn next-btn" onclick="navigateItem('next')" title="Next Item">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($relatedItems) && $relatedItems->count() > 0)
                <!-- Related Items -->
                <div class="related-items">
                    <h2 class="section-title">Related Items</h2>
                    <div class="related-grid">
                        @foreach($relatedItems as $item)
                        <div class="related-item">
                            <a href="{{ route('gallery.show', $item->id) }}">
                                <div class="related-image">
                                    <img src="{{ $item->thumbnail_path ? asset('storage/' . $item->thumbnail_path) : ($item->image_path ? asset('storage/' . $item->image_path) : asset('frontend/images/bg2.png')) }}" 
                                         alt="{{ $item->title }}" 
                                         loading="lazy">
                                </div>
                                <div class="related-content">
                                    <h3 class="related-title">{{ Str::limit($item->title, 30) }}</h3>
                                    <p class="related-date">{{ $item->created_at->format('M j, Y') }}</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Fullscreen Modal -->
    <div class="fullscreen-modal" id="fullscreenModal">
        <div class="fullscreen-content">
            <button class="fullscreen-close" onclick="closeFullscreen()">
                <i class="fas fa-times"></i>
            </button>
            <img src="" alt="" id="fullscreenImage">
            <div class="fullscreen-controls">
                <button class="control-btn" onclick="zoomIn()" title="Zoom In">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button class="control-btn" onclick="zoomOut()" title="Zoom Out">
                    <i class="fas fa-search-minus"></i>
                </button>
                <button class="control-btn" onclick="resetZoom()" title="Reset Zoom">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
    </div>

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

    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script>
        let currentZoom = 1;
        const maxZoom = 3;
        const minZoom = 0.5;

        // Fullscreen functionality
        function openFullscreen() {
            const modal = document.getElementById('fullscreenModal');
            const fullscreenImage = document.getElementById('fullscreenImage');
            const mainImage = document.getElementById('mainImage');
            
            fullscreenImage.src = mainImage.src;
            fullscreenImage.alt = mainImage.alt;
            modal.style.display = 'flex';
            
            // Reset zoom
            currentZoom = 1;
            updateImageZoom();
        }

        function closeFullscreen() {
            document.getElementById('fullscreenModal').style.display = 'none';
        }

        function zoomIn() {
            if (currentZoom < maxZoom) {
                currentZoom += 0.25;
                updateImageZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > minZoom) {
                currentZoom -= 0.25;
                updateImageZoom();
            }
        }

        function resetZoom() {
            currentZoom = 1;
            updateImageZoom();
        }

        function updateImageZoom() {
            const fullscreenImage = document.getElementById('fullscreenImage');
            fullscreenImage.style.transform = `scale(${currentZoom})`;
        }

        // Download functionality
        function downloadImage() {
            const mainImage = document.getElementById('mainImage');
            const link = document.createElement('a');
            link.href = mainImage.src;
            link.download = '{{ $gallery->title ?? "gallery-item" }}.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Share functionality
        function shareImage() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $gallery->title ?? "Gallery Item" }}',
                    text: '{{ $gallery->description ?? "Check out this gallery item from CSIRT PALI" }}',
                    url: window.location.href
                });
            } else {
                // Fallback to copying URL
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Image URL copied to clipboard!');
                });
            }
        }

        // Navigation (placeholder - would need actual implementation)
        function navigateItem(direction) {
            // This would require additional logic to get the next/previous gallery item
            console.log('Navigate ' + direction);
            // You could implement this by passing additional data from the controller
            // or making AJAX requests to get adjacent items
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('fullscreenModal');
            if (modal.style.display === 'flex') {
                switch(e.key) {
                    case 'Escape':
                        closeFullscreen();
                        break;
                    case '+':
                    case '=':
                        zoomIn();
                        break;
                    case '-':
                        zoomOut();
                        break;
                    case '0':
                        resetZoom();
                        break;
                    case 'ArrowLeft':
                        navigateItem('prev');
                        break;
                    case 'ArrowRight':
                        navigateItem('next');
                        break;
                }
            }
        });

        // Close modal when clicking outside image
        document.getElementById('fullscreenModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFullscreen();
            }
        });

        // Animate related items on scroll
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

        document.querySelectorAll('.related-item').forEach(item => {
            observer.observe(item);
        });
    </script>
</body>
</html>