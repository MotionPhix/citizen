<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Project;
use App\Models\NewsletterIssue;
use App\Models\NewsletterContent;
use App\Models\Subscriber;
use App\Models\ContactSubmission;
use App\Models\ImpactMetric;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating sample data for testing...');

        // Create sample blog posts
        $this->createSampleBlogs();

        // Create sample projects
        $this->createSampleProjects();

        // Create sample newsletter issues and content
        $this->createSampleNewsletters();

        // Create sample subscribers
        $this->createSampleSubscribers();

        // Create sample contact submissions
        $this->createSampleContactSubmissions();

        // Create sample impact metrics
        $this->createSampleImpactMetrics();

        $this->command->info('Sample data created successfully!');
    }

    private function createSampleBlogs(): void
    {
        // Get the first user (should be super admin)
        $firstUser = User::first();
        if (!$firstUser) {
            $this->command->warn('No users found, skipping blog creation');
            return;
        }

        $blogs = [
            [
                'title' => 'Community Garden Initiative Launches',
                'slug' => 'community-garden-initiative-launches',
                'excerpt' => 'Our new community garden project brings fresh produce and community spirit to downtown.',
                'content' => '<p>We are excited to announce the launch of our Community Garden Initiative, a project that will transform vacant lots into thriving green spaces that benefit our entire community.</p><p>This initiative will provide fresh, locally-grown produce while fostering community connections and environmental stewardship.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'user_id' => $firstUser->id,
            ],
            [
                'title' => 'Youth Leadership Program Success Stories',
                'slug' => 'youth-leadership-program-success-stories',
                'excerpt' => 'Meet the young leaders making a difference in our community through our mentorship program.',
                'content' => '<p>Our Youth Leadership Program has been instrumental in developing the next generation of community leaders. Here are some inspiring success stories from our participants.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'user_id' => $firstUser->id,
            ],
            [
                'title' => 'Upcoming Town Hall Meeting',
                'slug' => 'upcoming-town-hall-meeting',
                'excerpt' => 'Join us for our monthly town hall to discuss community priorities and upcoming initiatives.',
                'content' => '<p>Our monthly town hall meeting is scheduled for next week. This is your opportunity to voice concerns, share ideas, and learn about upcoming community projects.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'user_id' => $firstUser->id,
            ]
        ];

        foreach ($blogs as $blogData) {
            $blog = Blog::create($blogData);

            // Add some comments (mix of authenticated and anonymous)

            // Authenticated user comment
            Comment::create([
                'blog_id' => $blog->id,
                'user_id' => $firstUser->id,
                'content' => 'This is such a great initiative! How can I get involved?',
                'status' => Comment::STATUS_APPROVED,
                'approved_at' => now(),
                'approved_by' => $firstUser->id,
                'ip_address' => '127.0.0.1',
                'spam_score' => 0.0,
                'created_at' => now()->subDays(rand(1, 3)),
            ]);

            // Anonymous comment
            Comment::create([
                'blog_id' => $blog->id,
                'author_name' => 'Sarah Johnson',
                'author_email' => 'sarah.johnson@example.com',
                'content' => 'Thank you for sharing this information. It\'s very helpful for our community!',
                'status' => Comment::STATUS_APPROVED,
                'approved_at' => now(),
                'ip_address' => '192.168.1.100',
                'spam_score' => 0.1,
                'created_at' => now()->subDays(rand(1, 2)),
            ]);

            // Another anonymous comment (pending moderation)
            Comment::create([
                'blog_id' => $blog->id,
                'author_name' => 'Mike Wilson',
                'author_email' => 'mike.wilson@example.com',
                'author_website' => 'https://mikewilson.com',
                'content' => 'I have some questions about this project. Could someone please provide more details about the timeline?',
                'status' => Comment::STATUS_PENDING,
                'ip_address' => '192.168.1.101',
                'spam_score' => 0.2,
                'created_at' => now()->subHours(rand(1, 12)),
            ]);
        }

        $this->command->info('✓ Created sample blog posts with comments');
    }

    private function createSampleProjects(): void
    {
        $projects = [
            [
                'title' => 'Clean Water Access Initiative',
                'slug' => 'clean-water-access-initiative',
                'description' => 'Ensuring clean, safe drinking water for all community members through infrastructure improvements and education.',
                'content' => '<p>This comprehensive initiative focuses on improving water infrastructure and educating community members about water safety and conservation.</p>',
                'status' => 'current',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
                'funded_by' => 'Community Development Grant',
                'budget' => 150000,
                'people_reached' => 500,
                'is_featured' => true,
            ],
            [
                'title' => 'Digital Literacy Program',
                'slug' => 'digital-literacy-program',
                'description' => 'Providing computer and internet skills training to bridge the digital divide in our community.',
                'content' => '<p>Our digital literacy program offers free computer training, internet safety education, and technology support for community members of all ages.</p>',
                'status' => 'current',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(9),
                'funded_by' => 'Technology Foundation',
                'budget' => 75000,
                'people_reached' => 200,
                'is_featured' => false,
            ],
            [
                'title' => 'Senior Support Network',
                'slug' => 'senior-support-network',
                'description' => 'Creating a comprehensive support system for elderly community members including meal delivery and social activities.',
                'content' => '<p>The Senior Support Network provides essential services including meal delivery, transportation assistance, and social activities to help elderly community members maintain their independence and social connections.</p>',
                'status' => 'completed',
                'start_date' => now()->subYear(),
                'end_date' => now()->subMonths(2),
                'funded_by' => 'Senior Services Grant',
                'budget' => 50000,
                'people_reached' => 150,
                'is_featured' => false,
            ]
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        $this->command->info('✓ Created sample projects');
    }

    private function createSampleNewsletters(): void
    {
        // Create newsletter issues
        $issues = [
            [
                'title' => 'Community Update - March 2024',
                'description' => 'Monthly update featuring project progress, upcoming events, and community highlights.',
                'status' => 'sent',
                'published_at' => now()->subDays(15),
                'sent_at' => now()->subDays(15),
            ],
            [
                'title' => 'Spring Newsletter - April 2024',
                'description' => 'Spring edition with garden initiative updates and youth program highlights.',
                'status' => 'sent',
                'published_at' => now()->subDays(5),
                'sent_at' => now()->subDays(5),
            ],
            [
                'title' => 'May Community Digest',
                'description' => 'Upcoming town hall information and project updates.',
                'status' => 'draft',
                'published_at' => now()->addDays(5),
            ]
        ];

        foreach ($issues as $issueData) {
            $issue = NewsletterIssue::create($issueData);

            // Add content to each issue
            $contents = [
                [
                    'type' => 'story',
                    'title' => 'Community Garden Breaks Ground',
                    'excerpt' => 'Construction begins on our new community garden space.',
                    'content' => '<p>We are thrilled to announce that construction has officially begun on our community garden project.</p>',
                    'order' => 1,
                    'is_featured' => true,
                ],
                [
                    'type' => 'event',
                    'title' => 'Town Hall Meeting',
                    'excerpt' => 'Join us for our monthly community discussion.',
                    'content' => '<p>Our monthly town hall meeting is an opportunity for community members to engage with local leaders.</p>',
                    'metadata' => [
                        'location' => 'Community Center, Main Hall',
                        'start_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
                        'end_date' => now()->addDays(10)->addHours(2)->format('Y-m-d H:i:s'),
                    ],
                    'order' => 2,
                ],
                [
                    'type' => 'update',
                    'title' => 'Project Funding Update',
                    'excerpt' => 'Recent grants received for community initiatives.',
                    'content' => '<p>We are pleased to announce that we have received additional funding for our ongoing projects.</p>',
                    'category' => 'funding',
                    'order' => 3,
                ]
            ];

            foreach ($contents as $contentData) {
                $contentData['newsletter_issue_id'] = $issue->id;
                $contentData['published_at'] = now();
                NewsletterContent::create($contentData);
            }
        }

        $this->command->info('✓ Created sample newsletter issues and content');
    }

    private function createSampleSubscribers(): void
    {
        $subscribers = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com', 'status' => 'subscribed'],
            ['name' => 'Mary Johnson', 'email' => 'mary.johnson@example.com', 'status' => 'subscribed'],
            ['name' => 'Robert Brown', 'email' => 'robert.brown@example.com', 'status' => 'subscribed'],
            ['name' => 'Lisa Davis', 'email' => 'lisa.davis@example.com', 'status' => 'unsubscribed', 'unsubscribed_at' => now()->subDays(30)],
            ['name' => 'Michael Wilson', 'email' => 'michael.wilson@example.com', 'status' => 'subscribed'],
        ];

        foreach ($subscribers as $subscriberData) {
            Subscriber::create($subscriberData);
        }

        $this->command->info('✓ Created sample subscribers');
    }

    private function createSampleContactSubmissions(): void
    {
        $submissions = [
            [
                'name' => 'Alice Cooper',
                'email' => 'alice.cooper@example.com',
                'subject' => 'Question about Community Garden',
                'message' => 'I would like to know how I can volunteer for the community garden project. What are the requirements?',
                'status' => 'unread',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'David Lee',
                'email' => 'david.lee@example.com',
                'subject' => 'Suggestion for Youth Program',
                'message' => 'I have some ideas for expanding the youth leadership program. Would love to discuss them with someone.',
                'status' => 'read',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Emma Thompson',
                'email' => 'emma.thompson@example.com',
                'subject' => 'Thank You',
                'message' => 'Thank you for all the great work you do in our community. The senior support network has been incredibly helpful for my grandmother.',
                'status' => 'responded',
                'created_at' => now()->subDays(7),
            ]
        ];

        foreach ($submissions as $submissionData) {
            ContactSubmission::create($submissionData);
        }

        $this->command->info('✓ Created sample contact submissions');
    }

    private function createSampleImpactMetrics(): void
    {
        $metrics = [
            [
                'icon' => 'heroicon-o-users',
                'title' => 'Community Members Served',
                'metric' => '1,250',
                'description' => 'Total number of community members who have benefited from our programs',
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'icon' => 'heroicon-o-clock',
                'title' => 'Volunteer Hours',
                'metric' => '3,500',
                'description' => 'Total volunteer hours contributed to community projects',
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'icon' => 'heroicon-o-check-circle',
                'title' => 'Projects Completed',
                'metric' => '8',
                'description' => 'Number of community improvement projects completed this year',
                'is_published' => true,
                'sort_order' => 3,
            ]
        ];

        foreach ($metrics as $metricData) {
            ImpactMetric::create($metricData);
        }

        $this->command->info('✓ Created sample impact metrics');
    }
}
