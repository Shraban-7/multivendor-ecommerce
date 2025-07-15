<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .receipt-box {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .info-table,
        .info-table td {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 4px 0;
        }

        .section-title {
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        .screenshot {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="receipt-box">
        <h1>🧾 Payment Receipt</h1>
        <table class="info-table">
            <tr>
                <td><strong>Receipt ID:</strong> #PAYMENT_ID</td>
                <td style="text-align:right;"><strong>Date:</strong> 15 Jul 2025</td>
            </tr>
        </table>

        <div class="section-title">Customer Information</div>
        <table class="info-table">
            <tr>
                <td><strong>Name:</strong> John Doe</td>
                <td><strong>Phone:</strong> +8801XXXXXXXXX</td>
            </tr>
        </table>

        <div class="section-title">Payment Details</div>
        <table class="info-table">
            <tr>
                <td><strong>Method:</strong> bKash</td>
                <td><strong>Status:</strong> Pending</td>
            </tr>
            <tr>
                <td><strong>Amount:</strong> ৳1,200.00</td>
                <td><strong>Transaction ID:</strong> TX123456789</td>
            </tr>
        </table>

        <div class="section-title">Screenshot</div>
        <div class="screenshot">
            <img src="screenshot.jpg" alt="Payment Screenshot" style="width: 100%; max-height: 400px;">
        </div>
    </div>
</body>

</html>