<!DOCTYPE html>
<html>
<head>
    <title>Property Listing Rejected</title>
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
        h2 {
            color: #d9534f;
        }
        p {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        .reason-box {
            background-color: #ffe6e6;
            padding: 10px;
            border-left: 4px solid #d9534f;
            display: inline-block;
            text-align: left;
            margin: 15px 0;
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
