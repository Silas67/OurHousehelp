<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AppDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Service Charge
        DB::table('service_charges')->insertOrIgnore([
            ['amount' => 10000.00, 'currency' => 'NGN', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 2. Extra Service Cost
        DB::table('extra_service_costs')->insertOrIgnore([
            ['cost' => 12000.00, 'description' => 'Extra service cost for additional services', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. Extra Day Cost
        DB::table('extra_day_costs')->insertOrIgnore([
            ['cost' => 12000.00, 'description' => 'Extra day cost', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4. Services
        $services = [
            [
                'service_name' => 'Cleaning & Housekeeping',
                'base_cost' => 25000.00,
                'about_service' => 'Professional cleaning for a spotless, organized home. Our trained house helps provide thorough cleaning services including sweeping, mopping, dusting, and general tidying.',
                'icon_class' => 'fas fa-broom',
                'hours_of_work' => 4,
                'service_type' => 'PMS',
                'number_of_apartment' => false,
                'dos_and_donts' => 'Do: Provide cleaning supplies. Do: Specify areas of focus. Don\'t: Leave valuables unsecured. Don\'t: Expect services outside of agreed scope.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Laundry',
                'base_cost' => 20000.00,
                'about_service' => 'Fresh, clean laundry with attention to every detail. Our staff handles washing, drying, ironing, and folding of your clothes with care.',
                'icon_class' => 'fas fa-tshirt',
                'hours_of_work' => 3,
                'service_type' => 'PMS',
                'number_of_apartment' => false,
                'dos_and_donts' => 'Do: Sort delicate items separately. Do: Provide laundry products. Don\'t: Include items with special care requirements without informing staff.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Errand & Housekeeping',
                'base_cost' => 20000.00,
                'about_service' => 'Help with daily errands and keeping your home tidy. Our staff can handle grocery shopping, deliveries, bill payments, and general house maintenance.',
                'icon_class' => 'fas fa-running',
                'hours_of_work' => 4,
                'service_type' => 'PMS',
                'number_of_apartment' => false,
                'dos_and_donts' => 'Do: Provide clear errand instructions. Do: Give transportation fare if needed. Don\'t: Send staff to unsafe locations.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Nanny & Housekeeping',
                'base_cost' => 30000.00,
                'about_service' => 'Reliable childcare combined with housekeeping. Our nannies provide attentive care for your children while also maintaining a clean home environment.',
                'icon_class' => 'fas fa-baby',
                'hours_of_work' => 6,
                'service_type' => 'PMS',
                'number_of_apartment' => false,
                'dos_and_donts' => 'Do: Provide child\'s routine and dietary needs. Do: Leave emergency contacts. Don\'t: Leave children unattended before nanny arrival.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Cook & Errands',
                'base_cost' => 25000.00,
                'about_service' => 'Meal preparation and related errands for your culinary needs. Our cooks prepare delicious meals while also handling grocery shopping and kitchen management.',
                'icon_class' => 'fas fa-utensils',
                'hours_of_work' => 5,
                'service_type' => 'PMS',
                'number_of_apartment' => false,
                'dos_and_donts' => 'Do: Provide meal preferences and allergies. Do: Stock basic ingredients. Don\'t: Expect meals outside agreed menu without notice.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Driver',
                'base_cost' => 35000.00,
                'about_service' => 'Professional driving services for your daily commute and errands. Our experienced drivers ensure safe, comfortable transportation.',
                'icon_class' => 'fas fa-car',
                'hours_of_work' => 8,
                'service_type' => 'PSS',
                'number_of_apartment' => false,
                'dos_and_donts' => 'Do: Provide vehicle in good condition. Do: Fuel the vehicle. Don\'t: Expect driver to cover fuel costs. Don\'t: Request driving under unsafe conditions.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Plumbing',
                'base_cost' => 15000.00,
                'about_service' => 'Reliable plumbing solutions for your home. Our skilled plumbers handle repairs, installations, and maintenance of all plumbing fixtures.',
                'icon_class' => 'fas fa-wrench',
                'hours_of_work' => 3,
                'service_type' => 'PSS',
                'number_of_apartment' => false,
                'dos_and_donts' => 'Do: Describe the issue clearly. Do: Provide access to plumbing areas. Don\'t: Attempt DIY fixes before professional assessment.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->updateOrInsert(
                ['service_name' => $service['service_name']],
                $service
            );
        }

        // 5. Packages
        $packages = [
            [
                'name' => '1 Day Per Week',
                'cost' => 0.00,
                'description' => 'Basic package - 1 day of service per week',
                'point1' => 'Ideal for minimal needs',
                'point2' => 'Budget-friendly option',
                'point3' => 'Flexible scheduling',
                'days_per_week' => 1,
                'percentage' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '2 Days Per Week',
                'cost' => 5000.00,
                'description' => 'Standard package - 2 days of service per week',
                'point1' => 'Great for regular upkeep',
                'point2' => 'Balanced frequency',
                'point3' => 'Consistent service days',
                'days_per_week' => 2,
                'percentage' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '3 Days Per Week',
                'cost' => 10000.00,
                'description' => 'Premium package - 3 days of service per week',
                'point1' => 'Thorough maintenance',
                'point2' => 'Ideal for busy households',
                'point3' => 'Priority scheduling',
                'days_per_week' => 3,
                'percentage' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '4 Days Per Week',
                'cost' => 15000.00,
                'description' => 'Elite package - 4 days of service per week',
                'point1' => 'Near-daily coverage',
                'point2' => 'Best for large homes',
                'point3' => 'Dedicated house help',
                'days_per_week' => 4,
                'percentage' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '5 Days Per Week',
                'cost' => 20000.00,
                'description' => 'Full package - 5 days of service per week (Mon-Fri)',
                'point1' => 'Weekday coverage',
                'point2' => 'Full home management',
                'point3' => 'Top-tier service',
                'days_per_week' => 5,
                'percentage' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '6 Days Per Week',
                'cost' => 25000.00,
                'description' => 'Maximum package - 6 days of service per week (Mon-Sat)',
                'point1' => 'Almost daily coverage',
                'point2' => 'Complete home care',
                'point3' => 'VIP treatment',
                'days_per_week' => 6,
                'percentage' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($packages as $package) {
            DB::table('packages')->updateOrInsert(
                ['name' => $package['name']],
                $package
            );
        }

        // 6. Apartments
        $apartments = [
            [
                'name' => '1 Bedroom Flat',
                'cost' => 15000.00,
                'about' => 'Suitable for a 1-bedroom apartment',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '2 Bedroom Flat',
                'cost' => 20000.00,
                'about' => 'Suitable for a 2-bedroom apartment',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '3 Bedroom Flat',
                'cost' => 25000.00,
                'about' => 'Suitable for a 3-bedroom apartment',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '4 Bedroom Flat / Duplex',
                'cost' => 35000.00,
                'about' => 'Suitable for a 4-bedroom flat or duplex',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '5 Bedroom Duplex',
                'cost' => 45000.00,
                'about' => 'Suitable for a 5-bedroom duplex or mansion',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($apartments as $apartment) {
            DB::table('apartments')->updateOrInsert(
                ['name' => $apartment['name']],
                $apartment
            );
        }

        // 7. Admin user
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@ourhousehelp.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@ourhousehelp.com',
                'password' => Hash::make('admin123'),
                'account_type' => 'client',
                'user_role' => 1,
                'is_registered' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // 8. Settings - skipped (managed via admin panel)
    }
}
