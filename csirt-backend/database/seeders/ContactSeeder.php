<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Dr. Eduardo Martinez',
                'email' => 'eduardo.martinez@gov.mx',
                'phone' => '+52-55-1234-5678',
                'organization' => 'Mexican National CERT',
                'position' => 'Director of Cybersecurity',
                'country' => 'Mexico',
                'contact_type' => 'member',
                'message' => 'We are interested in joining the CSIRT PALI network and would like to discuss membership requirements and benefits.',
                'status' => 'contacted',
                'contacted_at' => now()->subDays(3),
                'notes' => '[2024-01-20 14:30] Initial contact made via phone. Scheduled follow-up meeting for next week to discuss membership details.'
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@cyber.br',
                'phone' => '+55-11-9876-5432',
                'organization' => 'Brazilian Cyber Defense Center',
                'position' => 'Senior Security Analyst',
                'country' => 'Brazil',
                'contact_type' => 'partner',
                'message' => 'We would like to establish a partnership for threat intelligence sharing and joint incident response exercises.',
                'status' => 'pending',
                'contacted_at' => null,
                'notes' => null
            ],
            [
                'name' => 'James Wilson',
                'email' => 'jwilson@cybersec.ca',
                'phone' => '+1-416-555-0123',
                'organization' => 'Canadian Centre for Cyber Security',
                'position' => 'Incident Response Coordinator',
                'country' => 'Canada',
                'contact_type' => 'emergency',
                'message' => 'URGENT: We are experiencing a coordinated cyber attack affecting critical infrastructure. Need immediate assistance and coordination.',
                'status' => 'resolved',
                'contacted_at' => now()->subDays(7),
                'notes' => '[2024-01-15 09:00] Emergency response team activated. Provided technical assistance and coordination support.\n[2024-01-16 16:30] Incident contained and resolved. Post-incident review scheduled.'
            ],
            [
                'name' => 'Ana Rodriguez',
                'email' => 'arodriguez@oas.org',
                'phone' => '+1-202-555-0456',
                'organization' => 'Organization of American States',
                'position' => 'Cybersecurity Program Manager',
                'country' => 'United States',
                'contact_type' => 'partner',
                'message' => 'The OAS is planning a regional cybersecurity summit and would like CSIRT PALI to participate as a key stakeholder and presenter.',
                'status' => 'contacted',
                'contacted_at' => now()->subDays(5),
                'notes' => '[2024-01-18 11:00] Positive response to participation request. Assigned speaker for threat intelligence session.'
            ],
            [
                'name' => 'Carlos Mendez',
                'email' => 'cmendez@security.co',
                'phone' => '+57-1-345-6789',
                'organization' => 'Colombian Cyber Police',
                'position' => 'Detective Supervisor',
                'country' => 'Colombia',
                'contact_type' => 'external',
                'message' => 'We are investigating a cybercrime case with international implications and need assistance with digital forensics and attribution.',
                'status' => 'pending',
                'contacted_at' => null,
                'notes' => null
            ],
            [
                'name' => 'Sofia Gutierrez',
                'email' => 'sofia.gutierrez@univ.pe',
                'phone' => '+51-1-987-6543',
                'organization' => 'Universidad Nacional Mayor de San Marcos',
                'position' => 'Cybersecurity Research Professor',
                'country' => 'Peru',
                'contact_type' => 'external',
                'message' => 'Our university is conducting research on regional cybersecurity cooperation models. We would like to interview CSIRT PALI representatives.',
                'status' => 'contacted',
                'contacted_at' => now()->subDays(1),
                'notes' => '[2024-01-22 15:45] Agreed to participate in research study. Interview scheduled for next week.'
            ],
            [
                'name' => 'Roberto Silva',
                'email' => 'rsilva@fintech.ar',
                'phone' => '+54-11-4567-8901',
                'organization' => 'Argentine FinTech Association',
                'position' => 'Chief Technology Officer',
                'country' => 'Argentina',
                'contact_type' => 'external',
                'message' => 'Our financial technology companies are facing increased cyber threats. We need guidance on best practices and incident response procedures.',
                'status' => 'pending',
                'contacted_at' => null,
                'notes' => null
            ],
            [
                'name' => 'Isabella Costa',
                'email' => 'icosta@ministry.cl',
                'phone' => '+56-2-2345-6789',
                'organization' => 'Chilean Ministry of Interior',
                'position' => 'Cybersecurity Advisor',
                'country' => 'Chile',
                'contact_type' => 'member',
                'message' => 'Chile is developing a national cybersecurity strategy and would like to align with CSIRT PALI frameworks and best practices.',
                'status' => 'contacted',
                'contacted_at' => now()->subDays(2),
                'notes' => '[2024-01-21 10:30] Provided framework documentation and offered consultation support for strategy development.'
            ],
            [
                'name' => 'Michael Thompson',
                'email' => 'mthompson@dhs.gov',
                'phone' => '+1-202-555-0789',
                'organization' => 'U.S. Department of Homeland Security',
                'position' => 'Senior Cybersecurity Analyst',
                'country' => 'United States',
                'contact_type' => 'partner',
                'message' => 'DHS is interested in strengthening cybersecurity cooperation in the Western Hemisphere. We would like to discuss collaboration opportunities.',
                'status' => 'resolved',
                'contacted_at' => now()->subDays(10),
                'notes' => '[2024-01-13 14:00] Productive meeting held. Established formal cooperation agreement for information sharing and joint exercises.'
            ],
            [
                'name' => 'Lucia Morales',
                'email' => 'lmorales@bank.ec',
                'phone' => '+593-2-345-6789',
                'organization' => 'Banco Central del Ecuador',
                'position' => 'Information Security Manager',
                'country' => 'Ecuador',
                'contact_type' => 'external',
                'message' => 'Our bank recently experienced a security incident and we need guidance on incident response procedures and forensic analysis.',
                'status' => 'pending',
                'contacted_at' => null,
                'notes' => null
            ]
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}