<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\User;
use App\Services\AmortizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AmortizationServiceTest extends TestCase
{
    use RefreshDatabase; // Use RefreshDatabase to ensure a clean database state for each test

    /** @test */
    public function it_generates_correct_amortization_schedule_for_a_loan()
    {
        $user = User::factory()->create();
        
        // Create a dummy loan for testing
        $loan = Loan::create([
            'user_id' => $user->id, // Assuming a user exists or mock one
            'principal_amount' => 100000.00,
            'interest_rate' => 0.05, // 5% annual interest
            'loan_term_months' => 12, // 12 months
            'start_date' => '2025-01-01',
        ]);

        $service = new AmortizationService();
        $schedule = $service->generateSchedule($loan);

        // Assertions for the generated schedule
        $this->assertCount(12, $schedule); // Should have 12 payments

        // Example: Assert the first payment details
        $firstPayment = $schedule[0];
        $this->assertEquals('2025-02-01', $firstPayment['payment_date']);
        // You would calculate expected values manually or using a known amortization calculator
        // For a $100,000 loan at 5% annual interest over 12 months:
        // Monthly Payment (M) = 100000 * (0.05/12 * (1 + 0.05/12)^12) / ((1 + 0.05/12)^12 - 1) = 8560.75
        // First month interest = 100000 * (0.05/12) = 416.67
        // First month principal = 8560.75 - 416.67 = 8144.08
        // Remaining balance = 100000 - 8144.08 = 91855.92

        $this->assertEquals(8144.08, $firstPayment['principal_component']);
        $this->assertEquals(416.67, $firstPayment['interest_component']);
        $this->assertEquals(8560.75, $firstPayment['total_payment']);
        $this->assertEquals(91855.92, $firstPayment['remaining_balance']);

        // Example: Assert the last payment details (adjust for potential rounding differences)
        $lastPayment = end($schedule);
        $this->assertEquals(0.00, round($lastPayment['remaining_balance'], 2)); // Remaining balance should be zero at the end

        // Add more assertions for other payments or edge cases (e.g., 0% interest, very short/long terms)
    }

    /** @test */
    public function it_handles_zero_interest_rate()
    {
        $user = User::factory()->create();

        $loan = Loan::create([
            'user_id' => $user->id,
            'principal_amount' => 12000.00,
            'interest_rate' => 0.00, // 0% annual interest
            'loan_term_months' => 12,
            'start_date' => '2025-01-01',
        ]);

        $service = new AmortizationService();
        $schedule = $service->generateSchedule($loan);

        $this->assertCount(12, $schedule);
        foreach ($schedule as $payment) {
            $this->assertEquals(0.00, $payment['interest_component']);
            $this->assertEquals(1000.00, $payment['principal_component']); // 12000 / 12
            $this->assertEquals(1000.00, $payment['total_payment']);
        }
        $this->assertEquals(0.00, round(end($schedule)['remaining_balance'], 2));
    }

    // more test methods for different scenarios and edge cases
}