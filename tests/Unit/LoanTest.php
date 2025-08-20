<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanTest extends TestCase
{
    use RefreshDatabase; // This will migrate and refresh your test database

    public function test_loan_creation()
    {
        // First create a user
        $user = User::factory()->create();
        
        // Then create a loan associated with that user
        $loan = Loan::create([
            'user_id' => $user->id,
            'principal_amount' => 12000,
            'interest_rate' => 0,
            'loan_term_months' => 12,
            'start_date' => '2025-01-01',
        ]);
        
        // Assert the loan was created successfully
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_loan_creation_with_factory()
    {
        $loan = Loan::factory()->create();
        
        $this->assertModelExists($loan);
        $this->assertDatabaseHas('loans', ['id' => $loan->id]);
    }
}