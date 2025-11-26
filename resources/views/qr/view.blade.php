<!DOCTYPE html>
<html>
<head>
    <title>Scan & Pay</title>
</head>
<body>

<h2>Scan & Pay Using UPI</h2>

<img src="{{ $qr_image }}" width="300" alt="QR Code">

<p><strong>QR ID:</strong> {{ $qr_id }}</p>
<p><strong>Amount:</strong> ₹{{ $amount }}</p>

</body>
</html>
