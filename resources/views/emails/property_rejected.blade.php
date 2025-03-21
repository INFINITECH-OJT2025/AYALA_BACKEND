<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing Rejected</title>
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
            color: #d9534f;
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
        .reason-box {
            background-color: #ffe6e6;
            padding: 12px;
            border-left: 4px solid #d9534f;
            border-radius: 4px;
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
        <h2>Your Property Listing Was Rejected</h2>
        <p>Hello <strong>{{ $property->first_name }}</strong>,</p>
        <p>Unfortunately, your property listing "<strong>{{ $property->property_name }}</strong>" did not meet our requirements and has been rejected.</p>

        <!-- ✅ Reason for Rejection -->
        <div class="reason-box">
            <p><strong>Reason:</strong> {{ $reason }}</p>
        </div>

        <p>You may edit your listing and resubmit it for review.</p>

        <!-- ✅ CTA Button to Resubmit -->
        <a href="{{ url('/properties/edit/' . $property->id) }}" class="btn">Edit & Resubmit</a>

        <p>If you have any questions, please contact our support team.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} AYALA LAND. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
