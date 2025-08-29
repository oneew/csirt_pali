<!-- Placeholder News Items (when no database data is available) -->
<article class="news-item">
    <div class="news-thumbnail">
        <img src="{{ asset('frontend/images/bg2.png') }}" alt="News Thumbnail">
        <div class="news-category vulnerability">Vulnerability</div>
    </div>
    <div class="news-content-area">
        <h3 class="news-title">
            <a href="#">Security Patch Released for Popular Framework</a>
        </h3>
        <p class="news-excerpt">
            A security patch has been released addressing multiple vulnerabilities in a widely used web framework. Organizations are advised to update immediately...
        </p>
        <div class="news-meta">
            <span class="news-author">
                <i class="fas fa-user"></i>
                Security Team
            </span>
            <span class="news-date">
                <i class="fas fa-calendar"></i>
                {{ date('F j, Y') }}
            </span>
            <span class="news-priority high">
                <i class="fas fa-flag"></i>
                High Priority
            </span>
        </div>
    </div>
</article>

<article class="news-item">
    <div class="news-thumbnail">
        <img src="{{ asset('frontend/images/Logo.png') }}" alt="News Thumbnail">
        <div class="news-category best-practices">Best Practices</div>
    </div>
    <div class="news-content-area">
        <h3 class="news-title">
            <a href="#">Updated Incident Response Guidelines for 2024</a>
        </h3>
        <p class="news-excerpt">
            New comprehensive guidelines for incident response teams, incorporating lessons learned from recent cyber incidents across the region...
        </p>
        <div class="news-meta">
            <span class="news-author">
                <i class="fas fa-user"></i>
                CSIRT PALI Team
            </span>
            <span class="news-date">
                <i class="fas fa-calendar"></i>
                {{ date('F j, Y', strtotime('-1 day')) }}
            </span>
            <span class="news-priority medium">
                <i class="fas fa-flag"></i>
                Medium Priority
            </span>
        </div>
    </div>
</article>

<article class="news-item">
    <div class="news-thumbnail">
        <img src="{{ asset('frontend/images/PALI.png') }}" alt="News Thumbnail">
        <div class="news-category incident-report">Incident Report</div>
    </div>
    <div class="news-content-area">
        <h3 class="news-title">
            <a href="#">Monthly Incident Summary - {{ date('F Y') }}</a>
        </h3>
        <p class="news-excerpt">
            Summary of cybersecurity incidents reported and handled by CSIRT PALI member organizations during {{ date('F Y') }}...
        </p>
        <div class="news-meta">
            <span class="news-author">
                <i class="fas fa-user"></i>
                Analytics Team
            </span>
            <span class="news-date">
                <i class="fas fa-calendar"></i>
                {{ date('F j, Y', strtotime('-2 days')) }}
            </span>
            <span class="news-priority low">
                <i class="fas fa-flag"></i>
                Low Priority
            </span>
        </div>
    </div>
</article>

<article class="news-item">
    <div class="news-thumbnail">
        <img src="{{ asset('frontend/images/bg2.png') }}" alt="News Thumbnail">
        <div class="news-category general">General</div>
    </div>
    <div class="news-content-area">
        <h3 class="news-title">
            <a href="#">New Training Program Announced for 2025</a>
        </h3>
        <p class="news-excerpt">
            CSIRT PALI announces a comprehensive training program for cybersecurity professionals scheduled for early 2025...
        </p>
        <div class="news-meta">
            <span class="news-author">
                <i class="fas fa-user"></i>
                Training Team
            </span>
            <span class="news-date">
                <i class="fas fa-calendar"></i>
                {{ date('F j, Y', strtotime('-3 days')) }}
            </span>
            <span class="news-priority medium">
                <i class="fas fa-flag"></i>
                Medium Priority
            </span>
        </div>
    </div>
</article>