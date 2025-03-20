<!DOCTYPE html>
<html>
<head>
    <title>Latest News Update</title>
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
        <h2>{{ $news['title'] }}</h2>
        <p class="meta">
            <strong>Category:</strong> {{ $news['category'] }} | 
            <strong>Published on:</strong> {{ \Carbon\Carbon::parse($news['published_at'])->format('F j, Y') }}
        </p>

        <p>{{ $news['content'] }}</p>

        <a href="http://localhost:3000/" class="btn">Read More</a>

        <p class="footer">Thank you for staying informed with our latest updates.</p>
    </div>
</body>
</html>
