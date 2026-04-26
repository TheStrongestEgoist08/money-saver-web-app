<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Expense Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            margin: 25px;
            color: #111827;
            line-height: 1.5;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 3px;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .header {
            border-bottom: 2px solid #10b981;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* SUMMARY */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary-table td {
            border: none;
            padding: 0 5px;
        }

        .summary-box {
            background: #f9fafb;
            border-radius: 8px;
            padding: 10px;
            height: 70px;
            text-align: center;
        }

        .summary-box h3 {
            margin: 0;
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .summary-box h2 {
            margin-top: 5px;
            font-size: 16px;
            color: #10b981;
        }

        /* SECTION */
        .section-title {
            font-size: 12px;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: bold;
            border-left: 3px solid #10b981;
            padding-left: 6px;
        }

        /* CHART FIX */
        .chart-container {
            text-align: center;
            margin: 10px 0 15px 0;
            page-break-inside: avoid;
        }

        .chart-box {
            display: inline-block;
            width: 100%;
            max-width: 500px;
        }

        .chart-box img {
            width: 100%;
            height: auto;
            max-height: 220px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        /* TABLE */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th {
            background-color: #f3f4f6;
            font-size: 10px;
            text-transform: uppercase;
            color: #374151;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 6px;
        }

        td {
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        /* PREVENT BREAK ISSUES */
        .no-break {
            page-break-inside: avoid;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>EXPENSE REPORT</h1>
        <p class="subtitle">
            Generated on {{ now()->format('F d, Y \a\t h:i A') }}
        </p>
    </div>

    <!-- SUMMARY -->
    <table class="summary-table">
        <tr>
            <td width="33%">
                <div class="summary-box">
                    <h3>Total Spent</h3>
                    <h2>₱{{ number_format($totalSpent, 2) }}</h2>
                </div>
            </td>
            <td width="33%">
                <div class="summary-box">
                    <h3>Transactions</h3>
                    <h2>{{ $transactionCount }}</h2>
                </div>
            </td>
            <td width="33%">
                <div class="summary-box">
                    <h3>Average</h3>
                    <h2>₱{{ number_format($average, 2) }}</h2>
                </div>
            </td>
        </tr>
    </table>

    <!-- CHARTS -->
    <div class="no-break">
        <div class="section-title">Expenses by Category</div>
        <div class="chart-container">
            <div class="chart-box">
                @if($barChartImage)
                    <img src="{{ $barChartImage }}">
                @endif
            </div>
        </div>
    </div>

    <div class="no-break">
        <div class="section-title">Daily Spending Trend</div>
        <div class="chart-container">
            <div class="chart-box">
                @if($lineChartImage)
                    <img src="{{ $lineChartImage }}">
                @endif
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="section-title">Detailed Transactions</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="15%">Category</th>
                <th width="20%">Name</th>
                <th width="30%">Description</th>
                <th width="20%" class="text-right">Amount (₱)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $exp)
            <tr>
                <td>{{ $exp->created_at->format('M d, Y') }}</td>
                <td>{{ ucfirst($exp->type) }}</td>
                <td>{{ $exp->expense_name }}</td>
                <td>{{ Str::limit($exp->description ?? '-', 50) }}</td>
                <td class="text-right">{{ number_format($exp->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        This report is system-generated.
    </div>

</body>
</html>
