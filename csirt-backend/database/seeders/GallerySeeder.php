<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@csirtpali.org')->first();
        $operatorUser = User::where('email', 'operator@csirtpali.org')->first();

        $galleryItems = [
            [
                'title' => 'CSIRT PALI Annual Conference 2023',
                'description' => 'Highlights from our annual conference held in Buenos Aires, featuring keynote speakers and technical workshops.',
                'image_path' => 'gallery/conference-2023-main.jpg',
                'thumbnail_path' => 'gallery/thumbs/conference-2023-main.jpg',
                'category' => 'conferences',
                'uploaded_by' => $adminUser->id,
                'is_featured' => true,
                'order' => 1,
                'metadata' => [
                    'width' => 1920,
                    'height' => 1080,
                    'file_size' => '2.5MB',
                    'camera' => 'Canon EOS R5',
                    'location' => 'Buenos Aires, Argentina'
                ]
            ],
            [
                'title' => 'Incident Response Training Workshop',
                'description' => 'Hands-on training session on advanced incident response techniques and digital forensics.',
                'image_path' => 'gallery/training-workshop-1.jpg',
                'thumbnail_path' => 'gallery/thumbs/training-workshop-1.jpg',
                'category' => 'training',
                'uploaded_by' => $operatorUser->id,
                'is_featured' => true,
                'order' => 2,
                'metadata' => [
                    'width' => 1600,
                    'height' => 900,
                    'file_size' => '1.8MB',
                    'location' => 'Mexico City, Mexico'
                ]
            ],
            [
                'title' => 'Cybersecurity Summit Panel Discussion',
                'description' => 'Expert panel discussing emerging threats and regional cooperation strategies.',
                'image_path' => 'gallery/panel-discussion.jpg',
                'thumbnail_path' => 'gallery/thumbs/panel-discussion.jpg',
                'category' => 'events',
                'uploaded_by' => $adminUser->id,
                'is_featured' => false,
                'order' => 3,
                'metadata' => [
                    'width' => 1400,
                    'height' => 800,
                    'file_size' => '1.2MB',
                    'location' => 'Santiago, Chile'
                ]
            ],
            [
                'title' => 'CSIRT PALI Team Meeting 2023',
                'description' => 'Annual team meeting bringing together CSIRT representatives from across the Americas.',
                'image_path' => 'gallery/team-meeting-2023.jpg',
                'thumbnail_path' => 'gallery/thumbs/team-meeting-2023.jpg',
                'category' => 'meetings',
                'uploaded_by' => $adminUser->id,
                'is_featured' => true,
                'order' => 4,
                'metadata' => [
                    'width' => 1800,
                    'height' => 1200,
                    'file_size' => '3.1MB',
                    'location' => 'Miami, USA'
                ]
            ],
            [
                'title' => 'Malware Analysis Lab Session',
                'description' => 'Technical training session on advanced malware analysis techniques and tools.',
                'image_path' => 'gallery/malware-lab.jpg',
                'thumbnail_path' => 'gallery/thumbs/malware-lab.jpg',
                'category' => 'training',
                'uploaded_by' => $operatorUser->id,
                'is_featured' => false,
                'order' => 5,
                'metadata' => [
                    'width' => 1500,
                    'height' => 1000,
                    'file_size' => '2.0MB',
                    'location' => 'Bogotá, Colombia'
                ]
            ],
            [
                'title' => 'International Cooperation Ceremony',
                'description' => 'Signing ceremony for new international cybersecurity cooperation agreements.',
                'image_path' => 'gallery/cooperation-ceremony.jpg',
                'thumbnail_path' => 'gallery/thumbs/cooperation-ceremony.jpg',
                'category' => 'events',
                'uploaded_by' => $adminUser->id,
                'is_featured' => false,
                'order' => 6,
                'metadata' => [
                    'width' => 1600,
                    'height' => 900,
                    'file_size' => '1.7MB',
                    'location' => 'Washington D.C., USA'
                ]
            ],
            [
                'title' => 'Cyber Exercise Simulation',
                'description' => 'Multi-national cyber exercise simulating coordinated response to major cyber incidents.',
                'image_path' => 'gallery/cyber-exercise.jpg',
                'thumbnail_path' => 'gallery/thumbs/cyber-exercise.jpg',
                'category' => 'training',
                'uploaded_by' => $operatorUser->id,
                'is_featured' => true,
                'order' => 7,
                'metadata' => [
                    'width' => 1920,
                    'height' => 1080,
                    'file_size' => '2.8MB',
                    'location' => 'São Paulo, Brazil'
                ]
            ],
            [
                'title' => 'Awards Ceremony 2023',
                'description' => 'Recognition ceremony honoring outstanding contributions to regional cybersecurity.',
                'image_path' => 'gallery/awards-ceremony.jpg',
                'thumbnail_path' => 'gallery/thumbs/awards-ceremony.jpg',
                'category' => 'events',
                'uploaded_by' => $adminUser->id,
                'is_featured' => false,
                'order' => 8,
                'metadata' => [
                    'width' => 1400,
                    'height' => 933,
                    'file_size' => '1.9MB',
                    'location' => 'Lima, Peru'
                ]
            ],
            [
                'title' => 'Youth Cybersecurity Program',
                'description' => 'Educational program introducing young professionals to cybersecurity careers.',
                'image_path' => 'gallery/youth-program.jpg',
                'thumbnail_path' => 'gallery/thumbs/youth-program.jpg',
                'category' => 'training',
                'uploaded_by' => $operatorUser->id,
                'is_featured' => false,
                'order' => 9,
                'metadata' => [
                    'width' => 1600,
                    'height' => 1067,
                    'file_size' => '2.2MB',
                    'location' => 'Guatemala City, Guatemala'
                ]
            ],
            [
                'title' => 'Emergency Response Drill',
                'description' => 'Regional emergency response drill testing coordination and communication protocols.',
                'image_path' => 'gallery/emergency-drill.jpg',
                'thumbnail_path' => 'gallery/thumbs/emergency-drill.jpg',
                'category' => 'training',
                'uploaded_by' => $adminUser->id,
                'is_featured' => false,
                'order' => 10,
                'metadata' => [
                    'width' => 1500,
                    'height' => 844,
                    'file_size' => '1.6MB',
                    'location' => 'Quito, Ecuador'
                ]
            ]
        ];

        foreach ($galleryItems as $item) {
            Gallery::create($item);
        }
    }
}