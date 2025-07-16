<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>

        :root {
            --primaryColor: #ffb321;
        }

        body {
            background-color: #f4f4f4;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333333;
        }

        .email-wrapper {
            max-width: 640px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background-color: var(--primaryColor);
            color: #ffffff;
            text-align: center;
            padding: 40px 20px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 26px;
        }

        .email-body {
            padding: 30px 40px;
        }

        .email-body h2 {
            font-size: 20px;
            margin-top: 0;
            color: var(--primaryColor);
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            margin: 15px 0;
        }

        .order-details {
            margin-top: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }

        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-details th,
        .order-details td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-details th {
            background-color: #f9f9f9;
            color: #555;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .cta {
            text-align: center;
            margin: 40px 0 20px;
        }

        .cta a {
            background-color: var(--primaryColor);
            color: #ffffff;
            padding: 14px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }

        .email-footer {
            text-align: center;
            font-size: 13px;
            color: #999999;
            padding: 20px;
        }

        @media only screen and (max-width: 640px) {
            .email-body {
                padding: 20px;
            }

            .order-details th,
            .order-details td {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="email-wrapper">
        <div class="email-header">
            <h1>Order Confirmed</h1>
            <p style="margin-top: 10px; font-size: 16px;">Thank you for shopping with us!</p>
        </div>

        <div class="email-body">
            <h2>Hello John Doe,</h2>
            <p>Your order <strong>#123456789</strong> has been successfully placed on <strong>July 16, 2025</strong>.</p>
            <p>We’ll notify you once it ships. Here’s a summary of your purchase:</p>

            <div class="order-details">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th style="text-align:right;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Wireless Mouse</td>
                            <td>1</td>
                            <td style="text-align:right;">$25.00</td>
                        </tr>
                        <tr>
                            <td>Keyboard</td>
                            <td>1</td>
                            <td style="text-align:right;">$45.00</td>
                        </tr>
                        <tr>
                            <td>USB-C Cable</td>
                            <td>2</td>
                            <td style="text-align:right;">$20.00</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="2">Total</td>
                            <td style="text-align:right;">$90.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="cta">
                <a href="https://www.example.com/order/123456789" target="_blank">View Your Order</a>
            </div>
        </div>

        <div class="email-footer">
            &copy; 2025 YourCompany Inc. All rights reserved.<br>
            Need help? <a href="mailto:support@example.com" style="color: #999999;">Contact Support</a>
        </div>
    </div>

</body>

</html>
