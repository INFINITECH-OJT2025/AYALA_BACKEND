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
        .btn-reschedule {
            background-color: #007bff;
            color: #ffffff;
        }
        .btn-reschedule:hover {
            background-color: #0056b3;
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
            <p>{!! nl2br(e($messageContent)) !!}</p>
        </div>

        <!-- ✅ Show message based on approval or rejection -->
        @if($status === 'approved')
            <p>Your reschedule request has been <strong>approved</strong>. Please be available on your new schedule:</p>
            <div class="message-box">
                <p><strong>New Schedule:</strong> {{ \Carbon\Carbon::parse($newSchedule)->format('F d, Y h:i A') }}</p>
            </div>
        @elseif($status === 'rejected')
            <p>Sorry.</p>
        @endif

        <!-- ✅ If admin scheduled the interview, show "Reschedule Interview" -->
        @if(strpos($subjectText, 'Interview Scheduled') !== false)
            <a href="{{ rtrim($frontendUrl, '/') }}" class="btn btn-reschedule">Reschedule Interview</a>
        @else
            <!-- ✅ If reschedule is approved or rejected, show "View Information" -->
            <a href="{{ rtrim($frontendUrl, '/') }}" class="btn btn-reschedule">
                View Information
            </a>
        @endif

        <!-- ✅ If rejected, show "View Other Job Listings" button -->
        @if($status === 'rejected')
            <a href="{{ rtrim($frontendUrl, '/') }}/jobs" class="btn btn-reject">View Other Job Listings</a>
        @endif

        <p>If you have any questions, feel free to contact our team.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} YourCompany. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
