<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'organization_name',
                'value' => 'CSIRT PALI',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Organization name displayed throughout the application',
                'is_public' => true
            ],
            [
                'key' => 'organization_description',
                'value' => 'CSIRT PALI is a collaborative network of cybersecurity incident response teams across the Americas, dedicated to strengthening cybersecurity, sharing critical information, and enhancing incident response capabilities among member countries.',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Short description of the organization',
                'is_public' => true
            ],
            [
                'key' => 'organization_mission',
                'value' => 'To build a safer and more resilient digital ecosystem through collaboration, training, and knowledge exchange among CSIRT teams in the Americas.',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Organization mission statement',
                'is_public' => true
            ],
            [
                'key' => 'organization_vision',
                'value' => 'To be the leading collaborative network for cybersecurity incident response in the Americas, fostering regional cooperation and excellence in cyber defense.',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Organization vision statement',
                'is_public' => true
            ],
            [
                'key' => 'organization_email',
                'value' => 'contact@csirtpali.org',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Primary contact email',
                'is_public' => true
            ],
            [
                'key' => 'emergency_email',
                'value' => 'emergency@csirtpali.org',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Emergency contact email',
                'is_public' => true
            ],
            [
                'key' => 'emergency_phone',
                'value' => '+1-555-CSIRT-1',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Emergency contact phone',
                'is_public' => true
            ],
            [
                'key' => 'address',
                'value' => '1234 Cyber Security Blvd, Washington, DC 20001, United States',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Physical address',
                'is_public' => true
            ],

            // Security Settings
            [
                'key' => 'max_upload_size',
                'value' => '10240',
                'type' => 'number',
                'group' => 'security',
                'description' => 'Maximum file upload size in KB',
                'is_public' => false
            ],
            [
                'key' => 'allowed_file_types',
                'value' => json_encode(['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip']),
                'type' => 'json',
                'group' => 'security',
                'description' => 'Allowed file types for uploads',
                'is_public' => false
            ],
            [
                'key' => 'session_timeout',
                'value' => '480',
                'type' => 'number',
                'group' => 'security',
                'description' => 'Session timeout in minutes',
                'is_public' => false
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'number',
                'group' => 'security',
                'description' => 'Minimum password length',
                'is_public' => false
            ],
            [
                'key' => 'enable_registration',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Allow new user registration',
                'is_public' => false
            ],
            [
                'key' => 'require_email_verification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Require email verification for new accounts',
                'is_public' => false
            ],
            [
                'key' => 'auto_approve_users',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Automatically approve new user registrations',
                'is_public' => false
            ],

            // Email Settings
            [
                'key' => 'notification_email',
                'value' => 'notifications@csirtpali.org',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Email address for system notifications',
                'is_public' => false
            ],
            [
                'key' => 'send_incident_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Send email notifications for new incidents',
                'is_public' => false
            ],
            [
                'key' => 'send_news_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Send email notifications for published news',
                'is_public' => false
            ],

            // Display Settings
            [
                'key' => 'items_per_page',
                'value' => '15',
                'type' => 'number',
                'group' => 'display',
                'description' => 'Number of items to display per page',
                'is_public' => false
            ],
            [
                'key' => 'default_timezone',
                'value' => 'America/New_York',
                'type' => 'text',
                'group' => 'display',
                'description' => 'Default timezone for the application',
                'is_public' => false
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d H:i:s',
                'type' => 'text',
                'group' => 'display',
                'description' => 'Default date format',
                'is_public' => false
            ],

            // Social Media Links
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/csirtpali',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Twitter profile URL',
                'is_public' => true
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/csirtpali',
                'type' => 'text',
                'group' => 'social',
                'description' => 'LinkedIn profile URL',
                'is_public' => true
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/c/csirtpali',
                'type' => 'text',
                'group' => 'social',
                'description' => 'YouTube channel URL',
                'is_public' => true
            ],

            // Features
            [
                'key' => 'enable_gallery',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Enable gallery functionality',
                'is_public' => false
            ],
            [
                'key' => 'enable_contact_form',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Enable contact form',
                'is_public' => false
            ],
            [
                'key' => 'enable_news_comments',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Enable comments on news articles',
                'is_public' => false
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}