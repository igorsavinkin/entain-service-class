<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $isEditing ? 'updateLoan' : 'createLoan' }}">
        @if($isEditing)
            <h3>Edit Loan</h3>
        @else
            <h3>Create New Loan</h3>
        @endif
        
        <div>
            <label for="principal_amount">Principal Amount:</label>
            <input type="number" step="0.01" id="principal_amount" wire:model="principal_amount">
            @error('principal_amount') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="interest_rate">Interest Rate (e.g., 0.05 for 5%):</label>
            <input type="number" step="0.0001" id="interest_rate" wire:model="interest_rate">
            @error('interest_rate') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="loan_term_months">Loan Term (Months):</label>
            <input type="number" id="loan_term_months" wire:model="loan_term_months">
            @error('loan_term_months') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" wire:model="start_date">
            @error('start_date') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            @if($isEditing)
                <button type="submit">Update Loan</button>
                <button type="button" wire:click="cancelEdit">Cancel</button>
            @else
                <button type="submit"  class="btn btn-success" style="background-color:rgb(24, 132, 46); color: white; margin-left: 5px;">Create Loan</button>
            @endif
        </div>
    </form>

    <h3>Current Loans</h3>
    <table border=1 >
        <thead>
            <tr>
                <th>ID</th>
                <th>Principal</th>
                <th>Interest Rate</th>
                <th>Term (Months)</th>
                <th>Start Date</th>
                <th>Schedule</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
                <tr>
                    <td>{{ $loan->id }}</td>
                    <td>{{ $loan->principal_amount }}</td>
                    <td>{{ $loan->interest_rate }}</td>
                    <td>{{ $loan->loan_term_months }}</td>
                    <td>{{ $loan->start_date }}</td>
                    <td>
                        <a target="_blank" href="/loans/{{ $loan->id }}/payments"
                         
                        class="text-blue-600 hover:underline">&nbsp; View Schedule &nbsp;</a>
                    </td>                    
                    <td>
                        <button wire:click="editLoan({{ $loan->id }})">Edit</button>
                        <button wire:click="deleteLoan({{ $loan->id }})" 
                                onclick="return confirm('Are you sure you want to delete this loan?')"
                                style="background-color: #dc3545; color: white; margin-left: 5px;">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>