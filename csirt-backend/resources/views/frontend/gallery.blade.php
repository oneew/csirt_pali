<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - CSIRT PALI</title>
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

    <section class="gallery-hero">
        <div class="gallery-hero-content">
            <h1 class="hero-title">Gallery</h1>
            <p class="hero-subtitle">
                Explore our collection of cybersecurity events, training sessions, and collaborative moments
            </p>
        </div>
    </section>

    <section class="gallery-section">
        <div class="container">
            @if(isset($categories) && $categories->count() > 0)
            <div class="gallery-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                @foreach($categories as $category)
                <button class="filter-btn" data-filter="{{ $category }}">{{ ucfirst($category) }}</button>
                @endforeach
            </div>
            @endif

            <div class="gallery-grid" id="gallery-grid">
                @if(isset($galleryItems) && $galleryItems->count() > 0)
                    @foreach($galleryItems as $item)
                    <div class="gallery-item" data-category="{{ $item->category ?? 'general' }}">
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('frontend/images/bg2.png') }}" alt="{{ $item->title }}" loading="lazy">
                        <div class="gallery-item-content">
                            <h3 class="gallery-item-title">{{ $item->title }}</h3>
                            <p class="gallery-item-date">{{ $item->created_at->format('F j, Y') }}</p>
                            @if($item->description)
                            <p class="gallery-item-description">{{ Str::limit($item->description, 100) }}</p>
                            @endif
                            @if($item->uploader)
                            <p class="gallery-item-uploader">
                                <i class="fas fa-user"></i>
                                {{ $item->uploader->full_name }}
                            </p>
                            @endif
                        </div>
                        <div class="gallery-item-overlay">
                            <button class="view-btn" onclick="openModal('{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('frontend/images/bg2.png') }}', '{{ addslashes($item->title) }}', '{{ addslashes($item->description ?? '') }}')">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Placeholder content when no gallery items exist -->
                    <div class="gallery-item" data-category="events">
                        <img src="{{ asset('frontend/images/bg2.png') }}" alt="Cybersecurity Conference 2024" loading="lazy">
                        <div class="gallery-item-content">
                            <h3 class="gallery-item-title">Cybersecurity Conference 2024</h3>
                            <p class="gallery-item-date">{{ date('F j, Y') }}</p>
                            <p class="gallery-item-description">Annual conference bringing together CSIRT teams from across the Americas to discuss emerging threats and best practices.</p>
                        </div>
                        <div class="gallery-item-overlay">
                            <button class="view-btn" onclick="openModal('{{ asset('frontend/images/bg2.png') }}', 'Cybersecurity Conference 2024', 'Annual conference bringing together CSIRT teams from across the Americas to discuss emerging threats and best practices.')">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>

                    <div class="gallery-item" data-category="training">
                        <img src="{{ asset('frontend/images/Logo.png') }}" alt="Incident Response Training" loading="lazy">
                        <div class="gallery-item-content">
                            <h3 class="gallery-item-title">Incident Response Training</h3>
                            <p class="gallery-item-date">{{ date('F j, Y', strtotime('-7 days')) }}</p>
                            <p class="gallery-item-description">Hands-on training session covering latest incident response methodologies and tools for CSIRT team members.</p>
                        </div>
                        <div class="gallery-item-overlay">
                            <button class="view-btn" onclick="openModal('{{ asset('frontend/images/Logo.png') }}', 'Incident Response Training', 'Hands-on training session covering latest incident response methodologies and tools for CSIRT team members.')">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>

                    <div class="gallery-item" data-category="meetings">
                        <img src="{{ asset('frontend/images/PALI.png') }}" alt="CSIRT PALI Team Meeting" loading="lazy">
                        <div class="gallery-item-content">
                            <h3 class="gallery-item-title">CSIRT PALI Team Meeting</h3>
                            <p class="gallery-item-date">{{ date('F j, Y', strtotime('-14 days')) }}</p>
                            <p class="gallery-item-description">Monthly coordination meeting to discuss ongoing security initiatives and threat landscape assessment.</p>
                        </div>
                        <div class="gallery-item-overlay">
                            <button class="view-btn" onclick="openModal('{{ asset('frontend/images/PALI.png') }}', 'CSIRT PALI Team Meeting', 'Monthly coordination meeting to discuss ongoing security initiatives and threat landscape assessment.')">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>

                    <div class="gallery-item" data-category="workshops">
                        <img src="{{ asset('frontend/images/bg2.png') }}" alt="Security Awareness Workshop" loading="lazy">
                        <div class="gallery-item-content">
                            <h3 class="gallery-item-title">Security Awareness Workshop</h3>
                            <p class="gallery-item-date">{{ date('F j, Y', strtotime('-21 days')) }}</p>
                            <p class="gallery-item-description">Educational workshop focused on building cybersecurity awareness among organizational stakeholders.</p>
                        </div>
                        <div class="gallery-item-overlay">
                            <button class="view-btn" onclick="openModal('{{ asset('frontend/images/bg2.png') }}', 'Security Awareness Workshop', 'Educational workshop focused on building cybersecurity awareness among organizational stakeholders.')">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>

                    <div class="gallery-item" data-category="events">
                        <img src="{{ asset('frontend/images/Logo.png') }}" alt="Regional Security Summit" loading="lazy">
                        <div class="gallery-item-content">
                            <h3 class="gallery-item-title">Regional Security Summit</h3>
                            <p class="gallery-item-date">{{ date('F j, Y', strtotime('-28 days')) }}</p>
                            <p class="gallery-item-description">High-level summit addressing regional cybersecurity challenges and collaborative defense strategies.</p>
                        </div>
                        <div class="gallery-item-overlay">
                            <button class="view-btn" onclick="openModal('{{ asset('frontend/images/Logo.png') }}', 'Regional Security Summit', 'High-level summit addressing regional cybersecurity challenges and collaborative defense strategies.')">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>

                    <div class="gallery-item" data-category="training">
                        <img src="{{ asset('frontend/images/PALI.png') }}" alt="Digital Forensics Workshop" loading="lazy">
                        <div class="gallery-item-content">
                            <h3 class="gallery-item-title">Digital Forensics Workshop</h3>
                            <p class="gallery-item-date">{{ date('F j, Y', strtotime('-35 days')) }}</p>
                            <p class="gallery-item-description">Advanced workshop on digital forensics techniques and tools for incident investigation and evidence collection.</p>
                        </div>
                        <div class="gallery-item-overlay">
                            <button class="view-btn" onclick="openModal('{{ asset('frontend/images/PALI.png') }}', 'Digital Forensics Workshop', 'Advanced workshop on digital forensics techniques and tools for incident investigation and evidence collection.')">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            @if(isset($galleryItems) && $galleryItems->hasPages())
            <!-- Pagination -->
            <div class="pagination">
                @if($galleryItems->onFirstPage())
                    <button class="pagination-btn prev" disabled>
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </button>
                @else
                    <a href="{{ $galleryItems->previousPageUrl() }}" class="pagination-btn prev">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif
                
                <div class="pagination-numbers">
                    @foreach($galleryItems->getUrlRange(1, $galleryItems->lastPage()) as $page => $url)
                        @if($page == $galleryItems->currentPage())
                            <button class="pagination-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                @if($galleryItems->hasMorePages())
                    <a href="{{ $galleryItems->nextPageUrl() }}" class="pagination-btn next">
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

    <!-- Modal for viewing images -->
    <div class="modal" id="imageModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <img id="modalImage" src="" alt="">
            <div class="modal-info">
                <h3 id="modalTitle"></h3>
                <p id="modalDescription"></p>
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
        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');

                galleryItems.forEach((item, index) => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.classList.add('loaded');
                        }, index * 100);
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('loaded');
                    }
                });
            });
        });

        // Modal functionality
        function openModal(imageSrc, title, description) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            
            modalImage.src = imageSrc;
            modalTitle.textContent = title;
            modalDescription.textContent = description;
            
            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Lazy loading animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('loaded');
                    }, Math.random() * 300);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        galleryItems.forEach(item => {
            observer.observe(item);
        });
    </script>
</body>
</html>