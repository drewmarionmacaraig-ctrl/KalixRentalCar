<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingPayment;
use App\Models\Inquiry;
use App\Models\PaymentMethod;
use App\Models\PaymentSetting;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@kalixrental.ph'],
            [
                'name' => 'KLX Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Payment settings (Percentage 50% default, Fixed 5000 option, 6% credit card fee)
        PaymentSetting::current();

        // Default Payment Methods
        $defaultMethods = [
            [
                'code' => 'gcash',
                'name' => 'GCash',
                'account_name' => 'KLX Affordable Cars for Rent',
                'account_number' => '0907-883-8861',
                'sort_order' => 1,
            ],
            [
                'code' => 'palawanpay',
                'name' => 'PalawanPay',
                'account_name' => 'KLX Affordable Cars for Rent',
                'account_number' => '0907-883-8861',
                'sort_order' => 2,
            ],
            [
                'code' => 'bank_transfer',
                'name' => 'Bank Transfer (BDO / BPI)',
                'account_name' => 'KLX Affordable Cars for Rent',
                'account_number' => '0012-3456-7890',
                'sort_order' => 3,
            ],
        ];
        foreach ($defaultMethods as $m) {
            PaymentMethod::firstOrCreate(['code' => $m['code']], $m + ['is_enabled' => true]);
        }

        // Add-ons
        $addons = [
            [
                'name' => 'Professional Driver',
                'description' => 'Experienced Palawan local driver (8 hrs/day, excludes driver meals/accommodation for out-of-town).',
                'pricing_type' => 'per_day',
                'price' => 1000.00,
                'is_active' => true,
            ],
            [
                'name' => 'Child Safety Seat',
                'description' => 'ISOFIX/belt-compatible toddler or infant car seat.',
                'pricing_type' => 'per_day',
                'price' => 250.00,
                'is_active' => true,
            ],
            [
                'name' => 'Airport / Hotel Delivery & Pickup',
                'description' => 'Convenient vehicle drop-off and pickup within Puerto Princesa City proper.',
                'pricing_type' => 'flat',
                'price' => 500.00,
                'is_active' => true,
            ],
            [
                'name' => 'El Nido / San Vicente Out-of-Town Permit',
                'description' => 'Extended northern Palawan travel clearance & roadside support.',
                'pricing_type' => 'flat',
                'price' => 1500.00,
                'is_active' => true,
            ],
            [
                'name' => 'Roof Rack / Cargo Carrier',
                'description' => 'Extra luggage capacity for surfboards or group travel.',
                'pricing_type' => 'per_day',
                'price' => 300.00,
                'is_active' => true,
            ],
        ];

        $createdAddons = [];
        foreach ($addons as $addonData) {
            $createdAddons[] = Addon::updateOrCreate(['name' => $addonData['name']], $addonData);
        }

        // Vehicles
        $vehicles = [
            [
                'name' => 'Toyota Innova 2.8 E',
                'brand' => 'Toyota',
                'model' => 'Innova',
                'year' => 2024,
                'plate_number' => 'KLX-1001',
                'category' => 'MPV',
                'transmission' => 'Automatic',
                'seats' => 7,
                'fuel_type' => 'Diesel',
                'daily_rate' => 3500.00,
                'status' => 'available',
                'is_featured' => true,
                'description' => 'Spacious 7-seater diesel MPV ideal for families and groups exploring Puerto Princesa, Sabang, and El Nido.',
            ],
            [
                'name' => 'Toyota Vios 1.3 XLE',
                'brand' => 'Toyota',
                'model' => 'Vios',
                'year' => 2024,
                'plate_number' => 'KLX-1002',
                'category' => 'Sedan',
                'transmission' => 'Automatic',
                'seats' => 5,
                'fuel_type' => 'Gasoline',
                'daily_rate' => 2000.00,
                'status' => 'available',
                'is_featured' => true,
                'description' => 'Fuel-efficient and easy-to-drive sedan, great for city errands and couples touring Palawan.',
            ],
            [
                'name' => 'Mitsubishi Xpander GLS',
                'brand' => 'Mitsubishi',
                'model' => 'Xpander',
                'year' => 2024,
                'plate_number' => 'KLX-1003',
                'category' => 'MPV',
                'transmission' => 'Automatic',
                'seats' => 7,
                'fuel_type' => 'Gasoline',
                'daily_rate' => 3000.00,
                'status' => 'available',
                'is_featured' => true,
                'description' => 'Modern 7-seater crossover MPV with high ground clearance and cold dual-zone air conditioning.',
            ],
            [
                'name' => 'Toyota Fortuner 2.4 G',
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'year' => 2023,
                'plate_number' => 'KLX-1004',
                'category' => 'SUV',
                'transmission' => 'Automatic',
                'seats' => 7,
                'fuel_type' => 'Diesel',
                'daily_rate' => 4500.00,
                'status' => 'rented',
                'is_featured' => false,
                'description' => 'Rugged 7-seater SUV built for long highway drives and rough coastal roads across Palawan.',
            ],
            [
                'name' => 'Toyota Hiace Commuter Deluxe',
                'brand' => 'Toyota',
                'model' => 'Hiace',
                'year' => 2023,
                'plate_number' => 'KLX-1005',
                'category' => 'Van',
                'transmission' => 'Manual',
                'seats' => 15,
                'fuel_type' => 'Diesel',
                'daily_rate' => 5000.00,
                'status' => 'available',
                'is_featured' => false,
                'description' => '15-seater passenger van for barkada trips, corporate outings, and airport transfers.',
            ],
            [
                'name' => 'Toyota Wigo 1.0 G',
                'brand' => 'Toyota',
                'model' => 'Wigo',
                'year' => 2024,
                'plate_number' => 'KLX-1006',
                'category' => 'Hatchback',
                'transmission' => 'Automatic',
                'seats' => 5,
                'fuel_type' => 'Gasoline',
                'daily_rate' => 1600.00,
                'status' => 'maintenance',
                'is_featured' => false,
                'description' => 'Compact, budget-friendly hatchback perfect for solo travelers or quick Puerto Princesa city trips.',
            ],
        ];

        $createdVehicles = [];
        foreach ($vehicles as $v) {
            $v['slug'] = Str::slug($v['name']);
            $createdVehicles[] = Vehicle::updateOrCreate(['plate_number' => $v['plate_number']], $v);
        }

        // Sample bookings if none exist
        if (Booking::count() === 0) {
            $fortuner = $createdVehicles[3];
            $innova = $createdVehicles[0];
            $vios = $createdVehicles[1];

            $b1 = Booking::create([
                'booking_code' => 'KLX-2026-0001',
                'vehicle_id' => $fortuner->id,
                'renter_name' => 'Marco Santos',
                'contact_no' => '0917-555-0142',
                'email' => 'marco.santos@example.com',
                'drivers_license_no' => 'N01-18-045210',
                'government_id_no' => 'P1234567A',
                'primary_driver_name' => 'Marco Santos',
                'primary_driver_license' => 'N01-18-045210',
                'pickup_datetime' => now()->subDays(1)->setTime(9, 0),
                'return_datetime' => now()->addDays(2)->setTime(17, 0),
                'pickup_location' => 'Puerto Princesa Airport',
                'return_location' => 'Foton Tagburos, Puerto Princesa',
                'route_intended_use' => 'Puerto Princesa - El Nido Tour',
                'daily_rate_snapshot' => 4500,
                'total_days' => 3,
                'base_amount' => 13500,
                'addons_amount' => 500,
                'processing_fee' => 0,
                'total_amount' => 14000,
                'down_payment_amount' => 7000,
                'remaining_balance' => 7000,
                'payment_option' => 'down_payment',
                'payment_method' => 'gcash',
                'status' => 'rented',
                'contract_accepted_at' => now()->subDays(2),
                'contract_signature_name' => 'Marco Santos',
                'release_checklist' => [
                    'exterior' => 'Clean, minor scratch on rear bumper',
                    'interior' => 'Clean, 7 seats intact',
                    'tires' => 'Good condition + spare',
                    'lights' => 'All working',
                    'fuel' => 'Full tank',
                    'accessories' => 'OR/CR copy, tools, jack',
                ],
            ]);

            BookingPayment::create([
                'booking_id' => $b1->id,
                'payment_type' => 'down_payment',
                'payment_method' => 'gcash',
                'amount' => 7000,
                'reference_number' => 'GC-908172635',
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(2),
            ]);

            $b2 = Booking::create([
                'booking_code' => 'KLX-2026-0002',
                'vehicle_id' => $innova->id,
                'renter_name' => 'Clarisse Mendoza',
                'contact_no' => '0918-234-8891',
                'email' => 'clarisse.m@example.com',
                'drivers_license_no' => 'D04-20-112934',
                'government_id_no' => 'UMID-0092-1122',
                'primary_driver_name' => 'Clarisse Mendoza',
                'primary_driver_license' => 'D04-20-112934',
                'pickup_datetime' => now()->addDays(2)->setTime(8, 0),
                'return_datetime' => now()->addDays(5)->setTime(8, 0),
                'pickup_location' => 'Foton Tagburos, Puerto Princesa',
                'return_location' => 'Foton Tagburos, Puerto Princesa',
                'route_intended_use' => 'Sabang Underground River & City Tour',
                'daily_rate_snapshot' => 3500,
                'total_days' => 3,
                'base_amount' => 10500,
                'addons_amount' => 750,
                'processing_fee' => 0,
                'total_amount' => 11250,
                'down_payment_amount' => 5625,
                'remaining_balance' => 5625,
                'payment_option' => 'down_payment',
                'payment_method' => 'palawanpay',
                'status' => 'payment_submitted',
                'contract_accepted_at' => now()->subHours(3),
                'contract_signature_name' => 'Clarisse Mendoza',
            ]);

            BookingAddon::create([
                'booking_id' => $b2->id,
                'addon_id' => $createdAddons[1]->id,
                'name_snapshot' => $createdAddons[1]->name,
                'pricing_type_snapshot' => $createdAddons[1]->pricing_type,
                'unit_price_snapshot' => $createdAddons[1]->price,
                'days' => 3,
                'subtotal' => 750,
            ]);

            BookingPayment::create([
                'booking_id' => $b2->id,
                'payment_type' => 'down_payment',
                'payment_method' => 'palawanpay',
                'amount' => 5625,
                'reference_number' => 'PP-445566778',
                'status' => 'pending',
            ]);

            $b3 = Booking::create([
                'booking_code' => 'KLX-2026-0003',
                'vehicle_id' => $vios->id,
                'renter_name' => 'Jerome Villanueva',
                'contact_no' => '0927-441-9023',
                'email' => 'jerome.v@example.com',
                'drivers_license_no' => 'N02-19-887766',
                'government_id_no' => 'DL-N02-19-887766',
                'primary_driver_name' => 'Jerome Villanueva',
                'primary_driver_license' => 'N02-19-887766',
                'pickup_datetime' => now()->subDays(10)->setTime(9, 0),
                'return_datetime' => now()->subDays(7)->setTime(9, 0),
                'actual_return_datetime' => now()->subDays(7)->setTime(9, 15),
                'pickup_location' => 'Puerto Princesa Airport',
                'return_location' => 'Puerto Princesa Airport',
                'route_intended_use' => 'Puerto Princesa City',
                'daily_rate_snapshot' => 2000,
                'total_days' => 3,
                'base_amount' => 6000,
                'addons_amount' => 500,
                'processing_fee' => 0,
                'total_amount' => 6500,
                'down_payment_amount' => 6500,
                'remaining_balance' => 0,
                'payment_option' => 'full',
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'contract_accepted_at' => now()->subDays(12),
                'contract_signature_name' => 'Jerome Villanueva',
            ]);

            BookingPayment::create([
                'booking_id' => $b3->id,
                'payment_type' => 'full',
                'payment_method' => 'bank_transfer',
                'amount' => 6500,
                'reference_number' => 'BDO-77123890',
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(12),
            ]);
        }

        // Sample inquiries
        if (Inquiry::count() === 0) {
            Inquiry::create([
                'name' => 'Angela Reyes',
                'contact_no' => '0917-882-3310',
                'email' => 'angela.reyes@example.com',
                'subject' => 'Group rate for 10-day Hiace rental',
                'message' => 'Hi! Do you offer a discounted rate if we book the Toyota Hiace for 10 days from Puerto Princesa to El Nido and back?',
                'vehicle_id' => $createdVehicles[4]->id,
                'status' => 'new',
            ]);
            Inquiry::create([
                'name' => 'David Tan',
                'contact_no' => '0998-112-4500',
                'email' => 'david.tan@example.com',
                'subject' => 'Early morning airport pickup',
                'message' => 'Our flight lands at 6:30 AM at PPS Airport. Can we arrange early vehicle release?',
                'status' => 'replied',
                'admin_notes' => 'Replied via Viber — confirmed 6:30 AM release is available with ₱500 airport delivery fee.',
            ]);
        }
    }
}
