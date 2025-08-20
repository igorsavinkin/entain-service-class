<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Payment;
use Carbon\Carbon;


class AmortizationService
{
    public function generateSchedule(Loan $loan)
    {
        $principal = $loan->principal_amount;
        $annualInterestRate = $loan->interest_rate;
        $loanTermMonths = $loan->loan_term_months;
        $startDate = Carbon::parse($loan->start_date);

        $monthlyInterestRate = $annualInterestRate / 12;

        // Calculate monthly payment (M)
        if ($monthlyInterestRate > 0) {
            $monthlyPayment = $principal * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $loanTermMonths)) /
                              (pow(1 + $monthlyInterestRate, $loanTermMonths) - 1);
        } else {
            $monthlyPayment = $principal / $loanTermMonths; // Simple interest for 0% rate
        }

        $remainingBalance = $principal;
        $payments = [];

        for ($i = 1; $i <= $loanTermMonths; $i++) {
            $interestComponent = $remainingBalance * $monthlyInterestRate;
            $principalComponent = $monthlyPayment - $interestComponent;

            // Adjust last payment to account for floating point inaccuracies
            if ($i == $loanTermMonths) {
                $principalComponent = $remainingBalance;
                $monthlyPayment = $principalComponent + $interestComponent;
            }

            $remainingBalance -= $principalComponent;

            $paymentDate = $startDate->copy()->addMonths($i);

            $payments[] = [
                'loan_id' => $loan->id,
                'payment_date' => $paymentDate->toDateString(),
                'principal_component' => round($principalComponent, 2),
                'interest_component' => round($interestComponent, 2),
                'total_payment' => round($monthlyPayment, 2),
                'remaining_balance' => round($remainingBalance, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // Delete existing payments for this loan
        Payment::where('loan_id', $loan->id)->delete();

        // Save the generated payment schedule to the payments table
        Payment::insert($payments);

        return $payments;
    }
}