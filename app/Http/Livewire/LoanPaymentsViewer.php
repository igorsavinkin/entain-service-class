<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Route;

use App\Models\Loan;
use Livewire\Component; 

class LoanPaymentsViewer extends Component
{
    public Loan $loan;

    public function mount(Loan $loan)
    { 
        $this->loan  = Route::current()->parameter('loan');
    }

    public function render()
    {
        $payments = $this->loan->payments()->orderBy("payment_date")->get(); 
        return view("livewire.loan-payments-viewer", compact("payments"));
    }
}