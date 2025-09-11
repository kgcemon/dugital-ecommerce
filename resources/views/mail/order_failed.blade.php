<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            padding: 30px;
            color: #333;
        }
        .mail-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f44336;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #f44336;
            margin: 0;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .order-info {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #f44336;
            border-radius: 5px;
            margin: 20px 0;
        }
        .order-info strong {
            display: inline-block;
            width: 120px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background: #f44336;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="mail-container">
    <div class="header">
        <h2>⚠️ Order Failed</h2>
    </div>
    <div class="content">
        <p>Hi <strong>{{ $name }}</strong>,</p>
        <p>Unfortunately, your order could not be processed. Please check your payment details and try again.</p>

        <div class="order-info">
            <p><strong>Order ID:</strong> #{{ $orderId }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Amount:</strong> {{ number_format($amount, 2) }} Tk</p>
            <p><strong>Status:</strong> Failed ❌</p>
        </div>

        <p>You can retry the payment from your account dashboard.</p>

        <p style="text-align: center;">
            <a href="{{ $retryUrl }}" class="btn">Retry Payment</a>
        </p>
    </div>

    <div class="footer">
        <p>Thank you for choosing <strong>Gaming Shop</strong>.
            If you need assistance, please contact our support team.</p>
    </div>
</div>
</body>
</html>
