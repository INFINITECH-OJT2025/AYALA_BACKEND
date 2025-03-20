<!DOCTYPE html>
<html>
<head>
    <title>New Job Opportunity at AyalaLand</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h2 {
            color: #004d40;
            margin-bottom: 10px;
            text-align: center;
        }
        .meta {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }
        p {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
            margin: 5px 0;
        }
        .btn {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #00796b;
            color: white;
            padding: 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #005a4a;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://www.ayalaland.com.ph/wp-content/uploads/2020/04/Ayala-Land-Logo-1.png" alt="AyalaLand Logo" class="logo">
            <h2>Exciting Career Opportunity at AyalaLand</h2>
        </div>

        <p class="meta">
            <strong>Location:</strong> {{ $job['location'] }}<br>
            <strong>Category:</strong> {{ $job['category'] }}
        </p>
        <p><strong>Job Title:</strong> {{ $job['title'] }}</p>
        <p><strong>Job Type:</strong> {{ $job['type'] ?? 'Not specified' }}</p>
        <p><strong>Salary:</strong> {{ $job['salary'] ?? 'Not specified' }}</p>
        <p><strong>Slots Available:</strong> {{ $job['slots'] }}</p>
        <p><strong>Description:</strong><br>{{ $job['description'] }}</p>

        <a href="https://www.ayalaland.com.ph/careers" class="btn">View Job Listings</a>

        <p class="footer">Stay updated with the latest career opportunities at AyalaLand.</p>
    </div>
</body>
</html>
