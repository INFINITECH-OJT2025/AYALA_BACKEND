<!DOCTYPE html>
<html>
<head>
    <title>New Job Opportunity</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: left;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #222;
            margin-bottom: 10px;
        }
        .meta {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }
        p {
            color: #444;
            font-size: 16px;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ $job['title'] }}</h2>
        <p class="meta">
            <strong>Location:</strong> {{ $job['location'] }} |
            <strong>Category:</strong> {{ $job['category'] }}
        </p>
        <p><strong>Job Type:</strong> {{ $job['type'] ?? 'Not specified' }}</p>
        <p><strong>Salary:</strong> {{ $job['salary'] ?? 'Not specified' }}</p>
        <p><strong>Slots Available:</strong> {{ $job['slots'] }}</p>
        <p>{{ $job['description'] }}</p>

        <a href="http://localhost:3000/" class="btn">View Jobs</a>

        <p class="footer">Thank you for staying updated with our latest job opportunities.</p>
    </div>
</body>
</html>
