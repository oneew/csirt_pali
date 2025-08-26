

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetSection.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Header scroll effect
    const header = document.querySelector('.header');
    let lastScrollTop = 0;
    
    function updateHeaderTransparency() {
        if (window.scrollY === 0) {
            header.classList.add('transparent');
        } else {
            header.classList.remove('transparent');
        }
    }
    window.addEventListener('scroll', updateHeaderTransparency);
    updateHeaderTransparency(); // Set awal saat load

    // Animate stats numbers on scroll
    const statsNumbers = document.querySelectorAll('.stats-number');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const finalNumber = parseInt(target.textContent);
                animateNumber(target, 0, finalNumber, 2000);
                statsObserver.unobserve(target);
            }
        });
    }, observerOptions);

    statsNumbers.forEach(number => {
        statsObserver.observe(number);
    });

    // Number animation function
    function animateNumber(element, start, end, duration) {
        const startTime = performance.now();
        const difference = end - start;
        
        function updateNumber(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentNumber = Math.floor(start + (difference * easeOutQuart));
            
            element.textContent = currentNumber;
            
            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            }
        }
        
        requestAnimationFrame(updateNumber);
    }

    // Service cards hover effect enhancement
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // CTA button click handler
    const ctaBtn = document.querySelector('.cta-btn');
    if (ctaBtn) {
        ctaBtn.addEventListener('click', function() {
            // Scroll to services section
            const servicesSection = document.querySelector('#servicios');
            if (servicesSection) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = servicesSection.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    }

    // Info button click handler
    const infoBtn = document.querySelector('.info-btn');
    if (infoBtn) {
        infoBtn.addEventListener('click', function() {
            // You could implement a modal or redirect to a requirements page
            alert('Para más información sobre los requerimientos para unirse, contacte directamente con CSIRTAmericas.');
        });
    }

    // Add loading animation
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
    });

    // Hapus efek parallax pada hero section

    // Form validation (if forms are added later)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add form validation logic here
            console.log('Form submitted');
        });
    });

    // Accessibility improvements
    // Add keyboard navigation support
    // Remove all code related to hamburger, navMenu, languageSelect, loginBtn

    // Add focus management for mobile menu
    // Remove all code related to hamburger, navMenu, languageSelect, loginBtn

    // Performance optimization: Lazy load images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        imageObserver.observe(img);
    });

    console.log('CSIRT website loaded successfully!');

    // Landing page functionality
    window.enterSite = function() {
        const landingSection = document.getElementById('landing');
        const mainContent = document.getElementById('mainContent');
        
        // Hide landing section
        landingSection.classList.add('hide');
        
        // Show main content after a short delay
        setTimeout(() => {
            landingSection.style.display = 'none';
            mainContent.style.display = 'block';
            
            // Trigger animation
            setTimeout(() => {
                mainContent.classList.add('show');
                // Ensure header transparency is updated after showing main content
                if (typeof updateHeaderTransparency === 'function') {
                    updateHeaderTransparency();
                }
            }, 100);
        }, 800);
    };

    // Optional: Add keyboard support for Enter key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && document.getElementById('landing').style.display !== 'none') {
            enterSite();
        }
    });

  

}); 
