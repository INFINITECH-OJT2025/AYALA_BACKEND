<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        h2 {
            color: #003865;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        p {
            color: #444;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .btn {
            display: block;
            background-color: #007bff;
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s ease-in-out;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .footer {
            font-size: 14px;
            color: #777;
            text-align: center;
            margin-top: 20px;
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
