<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaša narudžba je spremna za dostavu</title>
</head>
<body>
    <h2>Pozdrav, {{ $order->user->name }}!</h2>

    <p>Vaša narudžba <strong>#{{ $order->order_number }}</strong> je sada spremna za dostavu.</p>

    <p>Možete očekivati dostavu u roku 24h. U prilogu je vaša faktura.</p>

    <p>Hvala što ste kupovali kod nas!</p>

    <p><strong>Vaš Mbit</strong></p>
</body>
</html>
