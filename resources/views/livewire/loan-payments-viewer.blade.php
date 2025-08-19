<div>
    <h2>Amortization Schedule for Loan #{{ $loan->id }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/loans" class="small text-xsmall" >back to Loans</a>  </h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Payment Date</th>
                    <th class="py-2 px-4 border-b">Principal Component</th>
                    <th class="py-2 px-4 border-b">Interest Component</th>
                    <th class="py-2 px-4 border-b">Total Payment</th>
                    <th class="py-2 px-4 border-b">Remaining Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($payment->payment_date)->format("M d, Y") }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($payment->principal_component, 2) }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($payment->interest_component, 2) }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($payment->total_payment, 2) }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($payment->remaining_balance, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Basic CSS for responsiveness */
    .overflow-x-auto {
        overflow-x: auto;
    }
    .min-w-full {
        min-width: 100%;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        text-align: left;
        padding: 8px;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    @media screen and (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        tr {
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        td {
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            text-align: right;
        }
        td:before {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
        }
        td:nth-of-type(1):before { content: "Payment Date"; }
        td:nth-of-type(2):before { content: "Principal Component"; }
        td:nth-of-type(3):before { content: "Interest Component"; }
        td:nth-of-type(4):before { content: "Total Payment"; }
        td:nth-of-type(5):before { content: "Remaining Balance"; }
    }
</style>