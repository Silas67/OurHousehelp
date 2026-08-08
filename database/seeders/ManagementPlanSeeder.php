<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManagementPlanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Clear existing plans to allow re-running
        DB::table('management_plans')->truncate();

        DB::table('management_plans')->insert([
            [
                'slug' => 'premium',
                'name' => 'Premium Plan',
                'label' => 'PEACE OF MIND',
                'base_price' => 70000,
                'max_price' => null,
                'features' => json_encode([
                    'Full Supervision',
                    'Monthly Staff Monitoring',
                    'Free Replacement Anytime',
                    'Emergency Support',
                    'Staff Re-training',
                    'Conflict Resolution',
                    'Performance Oversight',
                    'Accountability',
                ]),
                'free_replacements' => -1, // unlimited
                'replacement_window' => 'anytime',
                'has_supervision' => true,
                'has_monitoring' => true,
                'has_mediation' => true,
                'has_emergency_support' => true,
                'has_retraining' => true,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'assisted',
                'name' => 'Assisted Plan',
                'label' => 'MOST CHOSEN BY FAMILIES',
                'base_price' => 40000,
                'max_price' => 50000,
                'features' => json_encode([
                    'Everything in Starter Plan',
                    'Company Mediation',
                    'Performance Check-ins',
                    '2 Free Replacements',
                    'Conflict Resolution',
                    'If staff fails to resume, we replace free',
                ]),
                'free_replacements' => 2,
                'replacement_window' => '30 days',
                'has_supervision' => false,
                'has_monitoring' => true,
                'has_mediation' => true,
                'has_emergency_support' => false,
                'has_retraining' => false,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'starter',
                'name' => 'Starter Plan',
                'label' => null,
                'base_price' => 25000,
                'max_price' => null,
                'features' => json_encode([
                    'Verified Staff Matching',
                    'Guarantor Checked',
                    '1 Replacement within 7 Days',
                    'Basic Support',
                ]),
                'free_replacements' => 1,
                'replacement_window' => '7 days',
                'has_supervision' => false,
                'has_monitoring' => false,
                'has_mediation' => false,
                'has_emergency_support' => false,
                'has_retraining' => false,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
