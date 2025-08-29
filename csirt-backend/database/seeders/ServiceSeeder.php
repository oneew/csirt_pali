<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Incident Response',
                'slug' => 'incident-response',
                'description' => 'Comprehensive incident response services for cybersecurity threats and breaches.',
                'content' => 'Our incident response service provides 24/7 support for handling cybersecurity incidents. We offer rapid response, forensic analysis, containment strategies, and recovery planning to minimize impact and restore normal operations.',
                'icon' => 'fas fa-shield-alt',
                'category' => 'incident_response',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
                'features' => [
                    '24/7 Emergency Response Hotline',
                    'Forensic Analysis and Evidence Collection',
                    'Malware Analysis and Reverse Engineering',
                    'Incident Containment and Eradication',
                    'Recovery Planning and Implementation',
                    'Post-Incident Review and Lessons Learned'
                ],
                'contact_email' => 'incident-response@csirtpali.org',
                'contact_phone' => '+1-555-CSIRT-1'
            ],
            [
                'name' => 'Threat Intelligence',
                'slug' => 'threat-intelligence',
                'description' => 'Real-time threat intelligence sharing and analysis across the Americas region.',
                'content' => 'Stay ahead of emerging threats with our comprehensive threat intelligence service. We collect, analyze, and disseminate actionable intelligence about current and emerging cybersecurity threats affecting the Americas region.',
                'icon' => 'fas fa-eye',
                'category' => 'threat_intelligence',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
                'features' => [
                    'Real-time Threat Feeds',
                    'Indicators of Compromise (IoCs)',
                    'Threat Actor Profiling',
                    'Campaign Analysis',
                    'Strategic Threat Assessments',
                    'Custom Intelligence Reports'
                ],
                'contact_email' => 'threat-intel@csirtpali.org',
                'contact_phone' => '+1-555-INTEL-1'
            ],
            [
                'name' => 'Security Training',
                'slug' => 'security-training',
                'description' => 'Professional cybersecurity training and capacity building programs.',
                'content' => 'Enhance your team\'s cybersecurity capabilities with our comprehensive training programs. We offer courses ranging from basic cybersecurity awareness to advanced incident response techniques.',
                'icon' => 'fas fa-graduation-cap',
                'category' => 'training',
                'is_active' => true,
                'is_featured' => true,
                'order' => 3,
                'features' => [
                    'Incident Response Training',
                    'Digital Forensics Workshops',
                    'Malware Analysis Courses',
                    'Threat Hunting Techniques',
                    'Cybersecurity Awareness Programs',
                    'Certification Preparation'
                ],
                'contact_email' => 'training@csirtpali.org',
                'contact_phone' => '+1-555-TRAIN-1'
            ],
            [
                'name' => 'Vulnerability Assessment',
                'slug' => 'vulnerability-assessment',
                'description' => 'Comprehensive vulnerability assessments and penetration testing services.',
                'content' => 'Identify and address security weaknesses before they can be exploited. Our vulnerability assessment service provides thorough evaluation of your systems and networks.',
                'icon' => 'fas fa-search',
                'category' => 'assessment',
                'is_active' => true,
                'is_featured' => false,
                'order' => 4,
                'features' => [
                    'Network Vulnerability Scanning',
                    'Web Application Security Testing',
                    'Penetration Testing',
                    'Social Engineering Assessments',
                    'Wireless Security Audits',
                    'Remediation Planning'
                ],
                'contact_email' => 'assessment@csirtpali.org',
                'contact_phone' => '+1-555-VULN-1'
            ],
            [
                'name' => 'Security Consultation',
                'slug' => 'security-consultation',
                'description' => 'Expert cybersecurity consulting and strategic guidance.',
                'content' => 'Get expert advice on cybersecurity strategy, policy development, and implementation. Our consultants provide tailored recommendations based on industry best practices.',
                'icon' => 'fas fa-comments',
                'category' => 'consultation',
                'is_active' => true,
                'is_featured' => false,
                'order' => 5,
                'features' => [
                    'Security Strategy Development',
                    'Policy and Procedure Creation',
                    'Compliance Assessment',
                    'Risk Management',
                    'Security Architecture Review',
                    'Business Continuity Planning'
                ],
                'contact_email' => 'consulting@csirtpali.org',
                'contact_phone' => '+1-555-CONSULT'
            ],
            [
                'name' => 'Malware Analysis',
                'slug' => 'malware-analysis',
                'description' => 'Advanced malware analysis and reverse engineering services.',
                'content' => 'Our malware analysis team provides in-depth analysis of malicious software to understand its capabilities, impact, and methods of operation.',
                'icon' => 'fas fa-bug',
                'category' => 'incident_response',
                'is_active' => true,
                'is_featured' => false,
                'order' => 6,
                'features' => [
                    'Static and Dynamic Analysis',
                    'Reverse Engineering',
                    'Behavioral Analysis',
                    'IoC Extraction',
                    'Attribution Analysis',
                    'Custom Signature Development'
                ],
                'contact_email' => 'malware@csirtpali.org',
                'contact_phone' => '+1-555-MALWARE'
            ],
            [
                'name' => 'Digital Forensics',
                'slug' => 'digital-forensics',
                'description' => 'Professional digital forensics and evidence collection services.',
                'content' => 'Comprehensive digital forensics services to support incident response and legal proceedings. Our experts follow industry best practices for evidence collection and analysis.',
                'icon' => 'fas fa-fingerprint',
                'category' => 'incident_response',
                'is_active' => true,
                'is_featured' => false,
                'order' => 7,
                'features' => [
                    'Evidence Collection and Preservation',
                    'Computer Forensics',
                    'Mobile Device Forensics',
                    'Network Forensics',
                    'Memory Forensics',
                    'Expert Testimony'
                ],
                'contact_email' => 'forensics@csirtpali.org',
                'contact_phone' => '+1-555-FORENSIC'
            ],
            [
                'name' => 'Threat Hunting',
                'slug' => 'threat-hunting',
                'description' => 'Proactive threat hunting and advanced persistent threat detection.',
                'content' => 'Proactively search for hidden threats in your environment with our threat hunting service. We use advanced techniques to identify sophisticated attacks that may have evaded traditional security measures.',
                'icon' => 'fas fa-crosshairs',
                'category' => 'threat_intelligence',
                'is_active' => true,
                'is_featured' => false,
                'order' => 8,
                'features' => [
                    'Hypothesis-driven Hunting',
                    'Behavioral Analytics',
                    'Advanced Persistent Threat Detection',
                    'Lateral Movement Analysis',
                    'Custom Hunt Development',
                    'Threat Intelligence Integration'
                ],
                'contact_email' => 'hunting@csirtpali.org',
                'contact_phone' => '+1-555-HUNT-1'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}