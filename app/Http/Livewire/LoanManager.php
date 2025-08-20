<?php

namespace App\Http\Livewire;

use App\Models\Loan;
use Livewire\Component;
use App\Services\AmortizationService;

class LoanManager extends Component
{
    public $principal_amount;
    public $interest_rate;
    public $loan_term_months;
    public $start_date;
    
    // Edit mode properties
    public $isEditing = false;
    public $editingLoanId = null;

    protected $rules = [
        'principal_amount' => 'required|numeric|min:0.01',
        'interest_rate' => 'required|numeric|min:0.0001|max:1',
        'loan_term_months' => 'required|integer|min:1',
        'start_date' => 'required|date',
    ];

    public function createLoan()
    {
        $this->validate();

        $loan = Loan::create([
            'user_id' => auth()->id(), // Assuming authenticated user
            'principal_amount' => $this->principal_amount,
            'interest_rate' => $this->interest_rate,
            'loan_term_months' => $this->loan_term_months,
            'start_date' => $this->start_date,
        ]);

        // Generate amortization schedule after loan creation
        $amortizationService = new AmortizationService();
        $amortizationService->generateSchedule($loan);

        $this->reset(); // Clear form fields after submission
        session()->flash('message', 'Loan created successfully.');
    }

    public function editLoan($loanId)
    {
        $loan = Loan::where('user_id', auth()->id())->findOrFail($loanId);
        
        $this->editingLoanId = $loan->id;
        $this->principal_amount = $loan->principal_amount;
        $this->interest_rate = $loan->interest_rate;
        $this->loan_term_months = $loan->loan_term_months;
        $this->start_date = $loan->start_date;
        $this->isEditing = true;
    }

    public function updateLoan()
    {
        $this->validate();

        $loan = Loan::where('user_id', auth()->id())->findOrFail($this->editingLoanId);
        
        $loan->update([
            'principal_amount' => $this->principal_amount,
            'interest_rate' => $this->interest_rate,
            'loan_term_months' => $this->loan_term_months,
            'start_date' => $this->start_date,
        ]);

        // Generate amortization schedule after loan update
        $amortizationService = new AmortizationService();
        $amortizationService->generateSchedule($loan);


        $this->cancelEdit();
        session()->flash('message', 'Loan updated successfully.');
    }

    public function cancelEdit()
    {
        $this->reset(['principal_amount', 'interest_rate', 'loan_term_months', 'start_date', 'isEditing', 'editingLoanId']);
    }

    public function deleteLoan($loanId)
    {
        $loan = Loan::where('user_id', auth()->id())->findOrFail($loanId);
        $loan->delete();
        
        // delete all payments for this loan
        Payment::where('loan_id', $loanId)->delete();

        session()->flash('message', 'Loan deleted successfully.');
    }

    public function render()
    {
        $loans = Loan::where('user_id', auth()->id())->get(); // Fetch loans for the current user
        return view('livewire.loan-manager', compact('loans'));
    }
}