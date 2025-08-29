<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@csirtpali.org')->first();
        $operatorUser = User::where('email', 'operator@csirtpali.org')->first();

        $newsArticles = [
            [
                'title' => 'CSIRT PALI Launches Enhanced Threat Intelligence Platform',
                'slug' => 'csirt-pali-launches-enhanced-threat-intelligence-platform',
                'excerpt' => 'New platform provides real-time threat intelligence sharing across the Americas region with advanced analytics and automated indicators.',
                'content' => '<p>CSIRT PALI is proud to announce the launch of our enhanced threat intelligence platform, designed to provide real-time threat intelligence sharing across the Americas region. This new platform represents a significant step forward in our mission to strengthen cybersecurity collaboration.</p>

<h3>Key Features</h3>
<ul>
<li>Real-time threat feed aggregation from multiple sources</li>
<li>Automated indicator of compromise (IoC) extraction</li>
<li>Advanced analytics and threat correlation</li>
<li>Secure sharing mechanisms between member organizations</li>
<li>Mobile-responsive interface for on-the-go access</li>
</ul>

<h3>Benefits for Members</h3>
<p>Member organizations will now have access to enhanced threat intelligence capabilities, including:</p>
<ul>
<li>Faster threat detection and response times</li>
<li>Improved situational awareness</li>
<li>Better coordination during multi-organizational incidents</li>
<li>Access to regional threat trends and patterns</li>
</ul>

<p>The platform will be available to all CSIRT PALI members starting next month. Training sessions will be conducted to ensure effective utilization of the new capabilities.</p>',
                'category' => 'general',
                'priority' => 'high',
                'status' => 'published',
                'author_id' => $adminUser->id,
                'tags' => ['threat intelligence', 'platform launch', 'collaboration'],
                'published_at' => now()->subDays(5),
                'is_featured' => true,
                'views_count' => 245
            ],
            [
                'title' => 'Critical Vulnerability Alert: CVE-2024-1234 - Immediate Action Required',
                'slug' => 'critical-vulnerability-alert-cve-2024-1234',
                'excerpt' => 'A critical vulnerability has been discovered in widely-used enterprise software. Immediate patching is recommended for all affected systems.',
                'content' => '<div class="alert alert-danger">
<strong>CRITICAL SECURITY ALERT</strong><br>
Severity: Critical (CVSS 9.8)<br>
CVE ID: CVE-2024-1234<br>
Affected Systems: Enterprise Software XYZ versions 1.0-3.5
</div>

<h3>Executive Summary</h3>
<p>A critical remote code execution vulnerability has been discovered in Enterprise Software XYZ that could allow attackers to gain complete control of affected systems without authentication.</p>

<h3>Technical Details</h3>
<ul>
<li><strong>Vulnerability Type:</strong> Remote Code Execution</li>
<li><strong>Attack Vector:</strong> Network</li>
<li><strong>Attack Complexity:</strong> Low</li>
<li><strong>Privileges Required:</strong> None</li>
<li><strong>User Interaction:</strong> None</li>
</ul>

<h3>Immediate Actions Required</h3>
<ol>
<li>Identify all systems running Enterprise Software XYZ versions 1.0-3.5</li>
<li>Apply emergency patch released by vendor immediately</li>
<li>If patching is not immediately possible, implement network-based controls to block access</li>
<li>Monitor systems for signs of compromise</li>
<li>Report any suspicious activity to your local CSIRT</li>
</ol>

<h3>Additional Resources</h3>
<p>For technical assistance or questions regarding this vulnerability, contact our incident response team at <a href="mailto:incident-response@csirtpali.org">incident-response@csirtpali.org</a>.</p>',
                'category' => 'vulnerability',
                'priority' => 'critical',
                'status' => 'published',
                'author_id' => $operatorUser->id,
                'tags' => ['vulnerability', 'critical', 'patch', 'CVE-2024-1234'],
                'published_at' => now()->subDays(2),
                'is_featured' => true,
                'views_count' => 892
            ],
            [
                'title' => 'New Ransomware Campaign Targeting Healthcare Sector',
                'slug' => 'new-ransomware-campaign-targeting-healthcare-sector',
                'excerpt' => 'Security researchers have identified a new ransomware campaign specifically targeting healthcare organizations across the Americas.',
                'content' => '<h3>Threat Overview</h3>
<p>Our threat intelligence team has identified a new ransomware campaign, dubbed "MedLock", that is specifically targeting healthcare organizations across the Americas region. The campaign has been active since early this month and has already affected several healthcare facilities.</p>

<h3>Attack Methodology</h3>
<ul>
<li><strong>Initial Access:</strong> Phishing emails with malicious attachments</li>
<li><strong>Persistence:</strong> Registry modifications and scheduled tasks</li>
<li><strong>Lateral Movement:</strong> SMB and RDP protocol exploitation</li>
<li><strong>Data Exfiltration:</strong> Patient records and financial data</li>
<li><strong>Encryption:</strong> AES-256 encryption of critical files</li>
</ul>

<h3>Indicators of Compromise</h3>
<ul>
<li>File Hash (SHA256): a1b2c3d4e5f6789012345678901234567890abcdef1234567890abcdef123456</li>
<li>Mutex: Global\\MedLock_2024</li>
<li>Registry Key: HKLM\\SOFTWARE\\MedLock</li>
<li>C2 Domains: medlock-c2[.]com, healthcare-update[.]net</li>
</ul>

<h3>Protective Measures</h3>
<ol>
<li>Implement email security controls to block malicious attachments</li>
<li>Ensure all systems are patched and up-to-date</li>
<li>Deploy endpoint detection and response (EDR) solutions</li>
<li>Conduct regular backups and test restoration procedures</li>
<li>Train staff on phishing awareness</li>
</ol>

<p>Healthcare organizations are advised to implement these protective measures immediately and report any suspicious activity to their local CSIRT.</p>',
                'category' => 'threat_intelligence',
                'priority' => 'high',
                'status' => 'published',
                'author_id' => $adminUser->id,
                'tags' => ['ransomware', 'healthcare', 'threat intelligence', 'MedLock'],
                'published_at' => now()->subDays(7),
                'is_featured' => false,
                'views_count' => 456
            ],
            [
                'title' => 'CSIRT PALI Annual Conference 2024 - Registration Now Open',
                'slug' => 'csirt-pali-annual-conference-2024-registration-open',
                'excerpt' => 'Join us for the annual CSIRT PALI conference featuring expert speakers, hands-on workshops, and networking opportunities.',
                'content' => '<h3>Conference Details</h3>
<p>We are excited to announce that registration is now open for the CSIRT PALI Annual Conference 2024. This year\'s theme is "Strengthening Cyber Resilience in the Digital Age".</p>

<ul>
<li><strong>Date:</strong> October 15-17, 2024</li>
<li><strong>Location:</strong> Miami Convention Center, Miami, FL, USA</li>
<li><strong>Format:</strong> Hybrid (In-person and Virtual)</li>
<li><strong>Registration Deadline:</strong> September 15, 2024</li>
</ul>

<h3>Conference Highlights</h3>
<ul>
<li>Keynote presentations from industry leaders</li>
<li>Technical workshops on emerging threats</li>
<li>Panel discussions on regional cyber challenges</li>
<li>Hands-on training sessions</li>
<li>Networking opportunities with regional CSIRTs</li>
<li>Exhibition featuring latest security technologies</li>
</ul>

<h3>Call for Papers</h3>
<p>We are also accepting submissions for technical presentations and research papers. Topics of interest include:</p>
<ul>
<li>Incident response methodologies</li>
<li>Threat intelligence sharing</li>
<li>Malware analysis techniques</li>
<li>International cooperation in cybersecurity</li>
<li>Emerging threats and vulnerabilities</li>
</ul>

<h3>Registration Information</h3>
<p>Early bird registration is available until August 31, 2024. Member organizations receive discounted rates.</p>

<p>For more information and to register, visit our conference website or contact <a href="mailto:conference@csirtpali.org">conference@csirtpali.org</a>.</p>',
                'category' => 'general',
                'priority' => 'medium',
                'status' => 'published',
                'author_id' => $adminUser->id,
                'tags' => ['conference', '2024', 'registration', 'training'],
                'published_at' => now()->subDays(10),
                'is_featured' => false,
                'views_count' => 178
            ],
            [
                'title' => 'Best Practices for Incident Response in Cloud Environments',
                'slug' => 'best-practices-incident-response-cloud-environments',
                'excerpt' => 'Learn about the unique challenges and best practices for incident response in cloud computing environments.',
                'content' => '<h3>Introduction</h3>
<p>As organizations increasingly adopt cloud technologies, incident response teams must adapt their procedures to address the unique challenges presented by cloud environments. This article outlines best practices for effective incident response in cloud computing environments.</p>

<h3>Cloud-Specific Challenges</h3>
<ul>
<li><strong>Shared Responsibility Model:</strong> Understanding the division of security responsibilities between cloud provider and customer</li>
<li><strong>Limited Forensic Capabilities:</strong> Restricted access to underlying infrastructure for forensic analysis</li>
<li><strong>Ephemeral Nature:</strong> Resources that can be created and destroyed dynamically</li>
<li><strong>Multi-tenancy:</strong> Shared infrastructure with other customers</li>
<li><strong>Jurisdiction Issues:</strong> Data may be stored in multiple geographic locations</li>
</ul>

<h3>Best Practices</h3>

<h4>1. Preparation</h4>
<ul>
<li>Develop cloud-specific incident response procedures</li>
<li>Establish relationships with cloud service providers</li>
<li>Implement comprehensive logging and monitoring</li>
<li>Create automated response capabilities</li>
</ul>

<h4>2. Detection and Analysis</h4>
<ul>
<li>Leverage cloud-native security tools</li>
<li>Implement security information and event management (SIEM)</li>
<li>Use cloud security posture management (CSPM) tools</li>
<li>Monitor API calls and configuration changes</li>
</ul>

<h4>3. Containment and Recovery</h4>
<ul>
<li>Use infrastructure as code for rapid deployment</li>
<li>Implement network segmentation and isolation</li>
<li>Leverage cloud-native backup and recovery services</li>
<li>Maintain offline copies of critical data</li>
</ul>

<h4>4. Lessons Learned</h4>
<ul>
<li>Conduct post-incident reviews specific to cloud challenges</li>
<li>Update procedures based on cloud service changes</li>
<li>Share lessons learned with the community</li>
</ul>

<h3>Conclusion</h3>
<p>Effective incident response in cloud environments requires adaptation of traditional procedures to address cloud-specific challenges. Organizations should invest in cloud security expertise and maintain close relationships with their cloud service providers.</p>',
                'category' => 'best_practices',
                'priority' => 'medium',
                'status' => 'published',
                'author_id' => $operatorUser->id,
                'tags' => ['cloud security', 'incident response', 'best practices'],
                'published_at' => now()->subDays(14),
                'is_featured' => false,
                'views_count' => 321
            ],
            [
                'title' => 'Quarterly Threat Landscape Report - Q2 2024',
                'slug' => 'quarterly-threat-landscape-report-q2-2024',
                'excerpt' => 'Comprehensive analysis of the cybersecurity threat landscape in the Americas region for the second quarter of 2024.',
                'content' => '<h3>Executive Summary</h3>
<p>This quarterly report provides an analysis of the cybersecurity threat landscape in the Americas region for Q2 2024, based on data collected from CSIRT PALI member organizations.</p>

<h3>Key Findings</h3>
<ul>
<li>25% increase in ransomware incidents compared to Q1 2024</li>
<li>Healthcare and financial sectors remain primary targets</li>
<li>Emergence of new malware families targeting cloud infrastructure</li>
<li>Increased use of social engineering in initial access</li>
</ul>

<h3>Incident Statistics</h3>
<ul>
<li><strong>Total Incidents:</strong> 1,247 (↑18% from Q1)</li>
<li><strong>Ransomware:</strong> 312 incidents (25% of total)</li>
<li><strong>Data Breaches:</strong> 198 incidents (16% of total)</li>
<li><strong>DDoS Attacks:</strong> 156 incidents (12% of total)</li>
<li><strong>Malware:</strong> 581 incidents (47% of total)</li>
</ul>

<h3>Sector Analysis</h3>
<ul>
<li><strong>Healthcare:</strong> 23% of all incidents</li>
<li><strong>Financial Services:</strong> 19% of all incidents</li>
<li><strong>Government:</strong> 15% of all incidents</li>
<li><strong>Education:</strong> 12% of all incidents</li>
<li><strong>Other:</strong> 31% of all incidents</li>
</ul>

<h3>Emerging Threats</h3>
<ul>
<li>CloudStealer: New malware targeting cloud credentials</li>
<li>AI-powered phishing campaigns</li>
<li>Supply chain attacks on software vendors</li>
<li>Attacks leveraging deepfake technology</li>
</ul>

<h3>Recommendations</h3>
<ol>
<li>Strengthen email security controls to combat phishing</li>
<li>Implement zero-trust network architecture</li>
<li>Enhance cloud security monitoring</li>
<li>Conduct regular security awareness training</li>
<li>Develop incident response capabilities for cloud environments</li>
</ol>

<p>The complete report with detailed technical analysis is available to CSIRT PALI members through our secure portal.</p>',
                'category' => 'threat_intelligence',
                'priority' => 'medium',
                'status' => 'published',
                'author_id' => $adminUser->id,
                'tags' => ['threat landscape', 'quarterly report', 'statistics', 'Q2 2024'],
                'published_at' => now()->subDays(21),
                'is_featured' => false,
                'views_count' => 567
            ]
        ];

        foreach ($newsArticles as $article) {
            News::create($article);
        }
    }
}