<!DOCTYPE html>
<html lang="hr">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Narudžba #{{ $order->order_number }} - Predračun</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2,
        h3 {
            color: #1e3a8a;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header .logo {
            width: 150px;
        }

        .header .company-info {
            text-align: right;
        }

        .order-info {
            margin-bottom: 20px;
        }

        .order-info p {
            font-size: 14px;
            line-height: 1.5;
        }

        .order-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-details-table th,
        .order-details-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-details-table th {
            background-color: #f3f4f6;
        }

        .summary {
            text-align: right;
            margin-top: 20px;
        }

        .summary p {
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            position: absolute;
            bottom: 15px;
            width: 100%;
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('storage/MbitShopLogo.png') }}" alt="Logo" width="150">
            </div>
            <div class="company-info">
                <h3>MBit Shop</h3>
                <p>Kostelska bb, 77000 Bihać, BiH</p>
                <p>Email: mehmed@mbitshop.com</p>
                <p>Phone: +387 60 300 4395</p>
            </div>
        </div>

        <div class="order-info">
            <h2>Narudžba #{{ $order->order_number }}</h2>
            <p><strong>Datum:</strong> {{ $order->created_at->format('F j, Y') }}</p>
            <p><strong>Kupac:</strong> {{ $order->user->name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Adresa:</strong> {{ $order->shipping_address }}</p>
        </div>

        <table class="order-details-table">
            <thead>
                <tr>
                    <th>Proizvod</th>
                    <th>Komada</th>
                    <th>Cijena</th>
                    <th>Ukupno</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>KM{{ number_format($item->product->price_with_discount, 2) }}</td>
                        <td>KM{{ number_format($item->quantity * $item->product->price_with_discount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <p><strong>Cijena:</strong> KM{{ number_format($subtotal, 2) }}</p>
            <p><strong>Dostava:</strong> KM{{ number_format($shipping, 2) }}</p>
            <p><strong>Ukupno:</strong> KM{{ number_format($total_price, 2) }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
        <p>Za narudžbe preko 100KM besplatna dostava!</p>
        <p>Hvala što kupujete kod nas!</p>
            <p>Ako imate pitanja kontaktirajte nas na: <a href="mailto:mehmed@mbitshop.com">mehmed@mbitshop.com</a></p>
        </div>
    </div>

</body>

</html>