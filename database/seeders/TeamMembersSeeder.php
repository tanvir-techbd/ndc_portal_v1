<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMembersSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['name' => 'Sk. Mofizur Rahman', 'designation' => 'Director (Data Center)'],
            ['name' => 'Mohammad Manirul Islam', 'designation' => 'Manager (Operation)'],
            ['name' => 'Md. Hossain Bin Amin', 'designation' => 'Manager (Operation)'],
            ['name' => 'Engr. Muztaba Seraji', 'designation' => 'Manager (Intelligent Building Mgmt) & Auditor (IT)'],
            ['name' => 'Biswajit Tarapdar', 'designation' => 'Manager (Network Operation)'],
            ['name' => 'Hasan Uj Jaman', 'designation' => 'Manager (Security Operation)'],
            ['name' => 'Engr. Ringko Kabiraj', 'designation' => 'Technical Staff', 'group' => 'technical_staff'],
            ['name' => 'Engr. Rezwana Sharmin', 'designation' => 'Analyst (Software Architecture)', 'group' => 'technical_staff'],
        ];

        foreach ($members as $i => $member) {
            TeamMember::firstOrCreate(
                ['name' => $member['name']],
                [
                    'designation' => $member['designation'],
                    'group' => $member['group'] ?? 'leadership',
                    'display_order' => $i + 1,
                ]
            );
        }

        $this->command->info('Seeded ' . count($members) . ' team members.');
    }
}
