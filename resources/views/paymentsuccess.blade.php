<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #22c55e;
            margin: 0 auto 30px;
            position: relative;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .checkmark::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 45px;
            border: solid white;
            border-width: 0 4px 4px 0;
            top: 10px;
            left: 28px;
            transform: rotate(45deg);
        }

        h1 {
            color: #1f2937;
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .message {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .details {
            background: #f9fafb;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-size: 14px;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
            font-size: 14px;
        }

        .amount {
            font-size: 18px;
            color: #22c55e;
        }

        .button {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 5px;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(6, 182, 212, 0.4);
        }

        .button-secondary {
            background: white;
            color: #06b6d4;
            border: 2px solid #06b6d4;
        }

        .button-secondary:hover {
            background: #f3f4f6;
            box-shadow: 0 10px 25px rgba(6, 182, 212, 0.2);
        }

        .transaction-id {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 40px 25px;
            }

            h1 {
                font-size: 26px;
            }

            .button {
                padding: 12px 30px;
                font-size: 14px;
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checkmark"></div>

        <h1>Payment Successful!</h1>

        <p class="message">
            Thank you for your purchase. Your payment has been processed successfully and a confirmation email has been sent to your registered email address.
        </p>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value">•••• 4242</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">Dec 09, 2025</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount Paid</span>
                <span class="detail-value amount">$149.99</span>
            </div>
        </div>

        <button class="button" onclick="window.location.href='/'">Continue Shopping</button>
        <button class="button button-secondary" onclick="window.print()">Download Receipt</button>

        <div class="transaction-id">
            Transaction ID: TXN-2025-12-09-A7B3C4D5
        </div>
    </div>
</body>
</html>
