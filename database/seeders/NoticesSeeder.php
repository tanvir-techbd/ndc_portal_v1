<?php

namespace Database\Seeders;

use App\Models\Notice;
use Illuminate\Database\Seeder;

class NoticesSeeder extends Seeder
{
    public function run(): void
    {
        $notices = [
            ['title' => 'NDC Expands IaaS Capacity to Support Digital Bangladesh Initiatives', 'category' => 'services', 'published_at' => '2026-06-15'],
            ['title' => 'ISO 27001 Recertification Successfully Completed for 2025–26', 'category' => 'security', 'published_at' => '2026-05-28'],
            ['title' => 'New Colocation Rack Units Available — Apply Through Service Portal', 'category' => 'general', 'published_at' => '2026-05-10'],
            ['title' => 'Zimbra Government Email Service Updated with Enhanced Storage Options', 'category' => 'services', 'published_at' => '2026-04-22'],
            ['title' => 'Scheduled maintenance window — July 1, 2026 (02:00–04:00 BDT)', 'category' => 'maintenance', 'published_at' => '2026-06-20'],
            ['title' => 'New Cloud Service Order portal launched — online applications now open', 'category' => 'services', 'published_at' => '2026-06-10'],
            ['title' => 'Service fee schedule update for FY 2026–27 — effective July 1, 2026', 'category' => 'policy', 'published_at' => '2026-05-25'],
            ['title' => 'Application deadline for FY2026 Managed Services contracts — June 30', 'category' => 'general', 'published_at' => '2026-05-12'],
            ['title' => 'NDC helpdesk number updated — new hotline: +88-02-55006840', 'category' => 'general', 'published_at' => '2026-04-30'],
            ['title' => 'Circular: Load Balancer service tier restructuring announcement', 'category' => 'policy', 'published_at' => '2026-04-15'],
            ['title' => 'Tender notice: Supply and installation of UPS equipment at NDC facility', 'category' => 'tender', 'published_at' => '2026-03-28'],
        ];

        foreach ($notices as $notice) {
            Notice::firstOrCreate(
                ['title' => $notice['title']],
                $notice + [
                    'body_html' => '<p>' . $notice['title'] . '</p>',
                    'status' => 'published',
                    'visibility' => 'public',
                ]
            );
        }

        $this->command->info('Seeded ' . count($notices) . ' notices.');
    }
}
