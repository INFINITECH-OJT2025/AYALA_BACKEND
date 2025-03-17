<!DOCTYPE html>
<html>
<head>
    <title>{{ $subjectText }}</title>
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
            color: #003865;
        }
        p {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 4px solid #007bff;
            display: inline-block;
            text-align: left;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn-approve {
            background-color: #28a745;
            color: #ffffff;
        }
        .btn-approve:hover {
            background-color: #218838;
        }
        .btn-reject {
            background-color: #d9534f;
            color: #ffffff;
        }
        .btn-reject:hover {
            background-color: #c9302c;
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
        <h2>{{ $subjectText }}</h2>

        <!-- ✅ Admin’s custom message -->
        <div class="message-box">
            <p>{{ $messageContent }}</p>
        </div>

        @if(strpos($subjectText, 'Approved') !== false)
            <a href="{{ url('/dashboard') }}" class="btn btn-approve">View Your Application</a>
        @else
            <a href="{{ url('/jobs') }}" class="btn btn-reject">View Other Job Listings</a>
        @endif

        <p>If you have any questions, feel free to contact our team.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} YourCompany. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
