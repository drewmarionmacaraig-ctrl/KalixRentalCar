<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\PaymentSetting;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KalixRentalFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_render_successfully(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('Drive more.');
        $this->get(route('catalog.index'))->assertOk()->assertSee('Browse vehicles');

        $vehicle = Vehicle::where('status', 'available')->firstOrFail();
        $this->get(route('catalog.show', $vehicle))->assertOk()->assertSee($vehicle->name);
        $this->get(route('inquiry.create'))->assertOk()->assertSee('Send us an inquiry');
    }

    public function test_inquiry_submission_persists_to_database(): void
    {
        $response = $this->post(route('inquiry.store'), [
            'name' => 'Test Customer',
            'contact_no' => '0917-123-4567',
            'email' => 'test@example.com',
            'subject' => 'Weekend trip',
            'message' => 'Is the Innova available next Friday?',
        ]);

        $response->assertRedirect(route('inquiry.create'));
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Test Customer',
            'subject' => 'Weekend trip',
        ]);
    }

    public function test_down_payment_percentage_and_fixed_options_work(): void
    {
        $settings = PaymentSetting::current();
        $settings->update(['down_payment_type' => 'percentage', 'down_payment_percentage' => 50]);
        $this->assertEquals(5000.00, $settings->computeDownPayment(10000));

        $settings->update(['down_payment_type' => 'fixed', 'down_payment_fixed_amount' => 5000]);
        $this->assertEquals(5000.00, $settings->computeDownPayment(14000));
        $this->assertEquals(3000.00, $settings->computeDownPayment(3000));
    }

    public function test_full_four_step_booking_flow_and_pdf_download(): void
    {
        Storage::fake('public');

        $vehicle = Vehicle::where('status', 'available')->firstOrFail();
        $addon = Addon::active()->firstOrFail();

        $pickup = now()->addDays(10)->setTime(9, 0)->format('Y-m-d\TH:i');
        $return = now()->addDays(12)->setTime(9, 0)->format('Y-m-d\TH:i');

        // Step 1: Checkout
        $checkoutRes = $this->post(route('booking.checkout.store', $vehicle), [
            'renter_name' => 'Juan Dela Cruz',
            'contact_no' => '0917-000-1111',
            'email' => 'juan@example.com',
            'drivers_license_no' => 'N01-22-123456',
            'drivers_license_image' => UploadedFile::fake()->image('license.jpg'),
            'government_id_no' => 'PH-99887766',
            'government_id_image' => UploadedFile::fake()->image('gov-id.jpg'),
            'pickup_datetime' => $pickup,
            'return_datetime' => $return,
            'pickup_location' => 'Foton Tagburos, Puerto Princesa',
            'return_location' => 'Foton Tagburos, Puerto Princesa',
            'route_intended_use' => 'El Nido Tour',
            'addons' => [$addon->id],
            'payment_option' => 'down_payment',
            'payment_method' => 'gcash',
        ]);

        $booking = Booking::where('renter_name', 'Juan Dela Cruz')->firstOrFail();
        $checkoutRes->assertRedirect(route('booking.contract', $booking));

        // Step 2: Contract error state when unchecked
        $this->post(route('booking.contract.accept', $booking), [
            'contract_signature_name' => '',
        ])->assertSessionHasErrors(['agree_terms', 'contract_signature_name']);

        // Step 2: Contract accepted
        $this->post(route('booking.contract.accept', $booking), [
            'agree_terms' => '1',
            'contract_signature_name' => 'Juan Dela Cruz',
        ])->assertRedirect(route('booking.payment', $booking));

        // Step 3: Payment proof upload
        $this->post(route('booking.payment.submit', $booking), [
            'reference_number' => 'GCASH-123456789',
            'proof_image' => UploadedFile::fake()->image('receipt.jpg'),
        ])->assertRedirect(route('booking.confirmation', $booking));

        $booking->refresh();
        $this->assertEquals('payment_submitted', $booking->status);

        // Step 4: Confirmation & Contract PDF download
        $this->get(route('booking.confirmation', $booking))->assertOk()->assertSee($booking->booking_code);
        $pdfRes = $this->get(route('booking.contract.pdf', $booking));
        $pdfRes->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfRes->headers->get('content-type'));
    }

    public function test_admin_pages_and_extension_conflict_detection(): void
    {
        $admin = User::where('email', 'admin@kalixrental.ph')->firstOrFail();

        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

        $this->actingAs($admin);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Dashboard');
        $this->get(route('admin.vehicles.index'))->assertOk();
        $this->get(route('admin.bookings.index'))->assertOk();
        $this->get(route('admin.inquiries.index'))->assertOk();
        $this->get(route('admin.addons.index'))->assertOk();
        $this->get(route('admin.payment-settings.edit'))->assertOk();
        $this->get(route('admin.reports.index'))->assertOk();

        // Create two consecutive bookings on the same vehicle to test extension conflict
        $vehicle = Vehicle::where('status', 'available')->firstOrFail();

        $b1 = Booking::create([
            'booking_code' => 'KLX-TEST-1001',
            'vehicle_id' => $vehicle->id,
            'renter_name' => 'Renter One',
            'contact_no' => '0917-111-1111',
            'drivers_license_no' => 'DL-1',
            'government_id_no' => 'ID-1',
            'primary_driver_name' => 'Renter One',
            'primary_driver_license' => 'DL-1',
            'pickup_datetime' => now()->addDays(20)->setTime(9, 0),
            'return_datetime' => now()->addDays(22)->setTime(9, 0),
            'pickup_location' => 'PPS',
            'return_location' => 'PPS',
            'daily_rate_snapshot' => 3000,
            'total_days' => 2,
            'base_amount' => 6000,
            'total_amount' => 6000,
            'down_payment_amount' => 3000,
            'remaining_balance' => 3000,
            'payment_option' => 'down_payment',
            'payment_method' => 'gcash',
            'status' => 'rented',
        ]);

        Booking::create([
            'booking_code' => 'KLX-TEST-1002',
            'vehicle_id' => $vehicle->id,
            'renter_name' => 'Renter Two',
            'contact_no' => '0917-222-2222',
            'drivers_license_no' => 'DL-2',
            'government_id_no' => 'ID-2',
            'primary_driver_name' => 'Renter Two',
            'primary_driver_license' => 'DL-2',
            'pickup_datetime' => now()->addDays(23)->setTime(9, 0),
            'return_datetime' => now()->addDays(25)->setTime(9, 0),
            'pickup_location' => 'PPS',
            'return_location' => 'PPS',
            'daily_rate_snapshot' => 3000,
            'total_days' => 2,
            'base_amount' => 6000,
            'total_amount' => 6000,
            'down_payment_amount' => 3000,
            'remaining_balance' => 3000,
            'payment_option' => 'down_payment',
            'payment_method' => 'gcash',
            'status' => 'confirmed',
        ]);

        // Extending b1 to day 24 overlaps b2 (days 23-25) -> conflict!
        $this->post(route('admin.bookings.extend.store', $b1), [
            'new_return_datetime' => now()->addDays(24)->setTime(9, 0)->format('Y-m-d\TH:i'),
            'rate_per_day' => 3000,
        ])->assertSessionHasErrors('conflict');
    }
}
