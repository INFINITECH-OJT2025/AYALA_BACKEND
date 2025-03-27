<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Opportunity at AyalaLand</title>
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
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 130px;
            margin-bottom: 15px;
        }
        h2 {
            color: #004d40;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .meta, p {
            color: #444;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .btn {
            display: block;
            background-color: #00796b;
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s ease-in-out;
            margin-top: 15px;
        }
        .btn:hover {
            background-color: #005a4a;
        }
        .unsubscribe {
            display: inline-block;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .unsubscribe a {
            color: #d32f2f;
            text-decoration: none;
        }
        .unsubscribe a:hover {
            text-decoration: underline;
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
        <div class="header">
            <!-- <img src="https://www.ayalaland.com.ph/wp-content/uploads/2020/04/Ayala-Land-Logo-1.png" alt="AyalaLand Logo" class="logo"> -->
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

        <p class="unsubscribe">
            If you no longer wish to receive our emails, you can 
            <a href="https://yourwebsite.com/unsubscribe">unsubscribe here</a>.
        </p>
    </div>
</body>
</html>
