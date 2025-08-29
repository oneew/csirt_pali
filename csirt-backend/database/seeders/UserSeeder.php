<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@csirtpali.org',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'organization' => 'CSIRT PALI',
            'country' => 'United States',
            'department' => 'Administration',
            'position' => 'Chief Information Security Officer',
            'phone' => '+1-555-0001',
            'is_active' => true,
            'email_verified_at' => now(),
            'bio' => 'Chief Information Security Officer at CSIRT PALI with over 15 years of experience in cybersecurity and incident response.',
            'permissions' => [
                'incidents.view', 'incidents.create', 'incidents.edit', 'incidents.delete', 'incidents.assign',
                'news.view', 'news.create', 'news.edit', 'news.delete', 'news.publish',
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'settings.view', 'settings.edit',
                'reports.view', 'reports.export'
            ]
        ]);

        // Create Operator
        User::create([
            'first_name' => 'John',
            'last_name' => 'Operator',
            'email' => 'operator@csirtpali.org',
            'password' => Hash::make('password123'),
            'role' => 'operator',
            'organization' => 'CSIRT PALI',
            'country' => 'United States',
            'department' => 'Operations',
            'position' => 'Security Operations Center Manager',
            'phone' => '+1-555-0002',
            'is_active' => true,
            'email_verified_at' => now(),
            'bio' => 'SOC Manager responsible for 24/7 monitoring and incident coordination across the Americas region.',
            'permissions' => [
                'incidents.view', 'incidents.create', 'incidents.edit', 'incidents.assign',
                'news.view', 'news.create', 'news.edit', 'news.publish',
                'reports.view'
            ]
        ]);

        // Create Analyst
        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Analyst',
            'email' => 'analyst@csirtpali.org',
            'password' => Hash::make('password123'),
            'role' => 'analyst',
            'organization' => 'CSIRT PALI',
            'country' => 'Canada',
            'department' => 'Analysis',
            'position' => 'Senior Security Analyst',
            'phone' => '+1-555-0003',
            'is_active' => true,
            'email_verified_at' => now(),
            'bio' => 'Senior Security Analyst specializing in malware analysis and threat intelligence.',
            'permissions' => [
                'incidents.view', 'incidents.create', 'incidents.edit',
                'news.view',
                'reports.view'
            ]
        ]);

        // Create Sample Viewer
        User::create([
            'first_name' => 'Bob',
            'last_name' => 'Viewer',
            'email' => 'viewer@csirtpali.org',
            'password' => Hash::make('password123'),
            'role' => 'viewer',
            'organization' => 'Government Agency XYZ',
            'country' => 'Mexico',
            'department' => 'IT Security',
            'position' => 'IT Security Specialist',
            'phone' => '+52-555-0004',
            'is_active' => true,
            'email_verified_at' => now(),
            'bio' => 'IT Security Specialist representing Government Agency XYZ in the CSIRT PALI network.',
            'permissions' => [
                'incidents.view',
                'news.view'
            ]
        ]);

        // Create Additional Sample Users
        $sampleUsers = [
            [
                'first_name' => 'Maria',
                'last_name' => 'Rodriguez',
                'email' => 'maria.rodriguez@csirtpali.org',
                'role' => 'analyst',
                'organization' => 'CSIRT Brasil',
                'country' => 'Brazil',
                'department' => 'Incident Response',
                'position' => 'Incident Response Specialist'
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'Silva',
                'email' => 'carlos.silva@csirtpali.org',
                'role' => 'operator',
                'organization' => 'CSIRT Colombia',
                'country' => 'Colombia',
                'department' => 'Operations',
                'position' => 'Security Operations Analyst'
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Garcia',
                'email' => 'ana.garcia@csirtpali.org',
                'role' => 'analyst',
                'organization' => 'CSIRT Argentina',
                'country' => 'Argentina',
                'department' => 'Threat Intelligence',
                'position' => 'Threat Intelligence Analyst'
            ],
        ];

        foreach ($sampleUsers as $userData) {
            User::create(array_merge($userData, [
                'password' => Hash::make('password123'),
                'phone' => '+' . rand(1, 99) . '-555-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'is_active' => true,
                'email_verified_at' => now(),
                'bio' => 'Member of the CSIRT PALI network representing ' . $userData['organization'] . '.',
                'permissions' => $userData['role'] === 'analyst' ? [
                    'incidents.view', 'incidents.create', 'incidents.edit',
                    'news.view',
                    'reports.view'
                ] : [
                    'incidents.view', 'incidents.create', 'incidents.edit', 'incidents.assign',
                    'news.view', 'news.create', 'news.edit',
                    'reports.view'
                ]
            ]));
        }
    }
}