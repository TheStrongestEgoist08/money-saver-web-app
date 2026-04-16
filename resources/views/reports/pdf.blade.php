<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formal Expense Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #1f2937;
            line-height: 1.6;
        }
        h1 { text-align: center; color: #111827; margin-bottom: 8px; }
        .subtitle { text-align: center; color: #6b7280; margin-bottom: 35px; }

        /* Summary - 1 Row, 3 Columns using Table */
        .summary-table {
            width: 100%;
            margin: 30px 0;
            border-collapse: separate;
            border-spacing: 12px;
        }
        .summary-box {
            border: 2px solid #e5e7eb;
            padding: 20px 15px;
            text-align: center;
            border-radius: 12px;
            background-color: #f9fafb;
        }
        .summary-box h3 {
            margin: 0 0 8px 0;
            color: #374151;
            font-size: 13px;
        }
        .summary-box h2 {
            margin: 0;
            font-size: 24px;
            color: #10b981;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .text-right { text-align: right; }

        .chart-container {
            text-align: center;
            margin: 40px 0;
            page-break-inside: avoid;
        }
        img {
            max-width: 100%;
            height: auto;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
        }
        .header {
            border-bottom: 4px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FORMAL EXPENSE REPORT</h1>
        <p class="subtitle">Generated on: {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <!-- Summary - Now 1 Row, 3 Columns -->
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
                    <h3>Total Transactions</h3>
                    <h2>{{ $transactionCount }}</h2>
                </div>
            </td>
            <td width="33%">
                <div class="summary-box">
                    <h3>Average per Transaction</h3>
                    <h2>₱{{ number_format($average, 2) }}</h2>
                </div>
            </td>
        </tr>
    </table>

    <!-- Charts -->
    <div class="chart-container">
        <h2>Expenses by Category</h2>
        @if($barChartImage)
            <img src="{{ $barChartImage }}" alt="Expenses by Category">
        @endif
    </div>

    <div class="chart-container">
        <h2>Daily Spending Trend</h2>
        @if($lineChartImage)
            <img src="{{ $lineChartImage }}" alt="Daily Spending Trend">
        @endif
    </div>

    <!-- Detailed Table -->
    <h2>Detailed Expense Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Expense Name</th>
                <th>Description</th>
                <th class="text-right">Amount (₱)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $exp)
            <tr>
                <td>{{ $exp->created_at->format('M d, Y') }}</td>
                <td>{{ $exp->type }}</td>
                <td>{{ $exp->expense_name }}</td>
                <td>{{ Str::limit($exp->description ?? '-', 80) }}</td>
                <td class="text-right">{{ number_format($exp->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align:center; margin-top:50px; color:#9ca3af; font-size:11px;">
        This is a computer-generated formal report.
    </p>
</body>
</html>
