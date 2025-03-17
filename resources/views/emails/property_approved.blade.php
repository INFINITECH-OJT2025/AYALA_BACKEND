<!DOCTYPE html>
<html>
<head>
    <title>Property Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        h2 {
            color: #003865;
        }
        p {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="container">

        <!-- ✅ Email Content -->
        <h2>Congratulations, Your Property is Now Live! 🎉</h2>
        <p>Hello <strong>{{ $property->first_name }}</strong>,</p>
        <p>Your property titled "<strong>{{ $property->property_name }}</strong>" has been approved and is now listed on our website.</p>

        <!-- ✅ CTA Button -->
        <a href="{{ url('/properties/' . $property->id) }}" class="btn">View Your Listing</a>

        <p>Thank you for choosing our platform!</p>

        <!-- ✅ Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} AYALA LAND. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
