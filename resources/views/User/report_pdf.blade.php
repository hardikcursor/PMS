<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $vehicleType }} Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background: #f0f0f0; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>{{ strtoupper($vehicleType) }} Vehicle Report</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Vehicle Type</th>
                <th>Qty</th>
                <th>Total Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($reports as $key => $report)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $report->vehicle_type }}</td>
                    <td>{{ $report->qty }}</td>
                    <td>₹{{ number_format($report->total_amount, 2) }}</td>
                </tr>
                @php $grandTotal += $report->total_amount; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="font-weight: bold;">Grand Total</td>
                <td style="font-weight: bold;">₹{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
