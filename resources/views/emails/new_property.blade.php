<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Property Available</title>
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
        }
        h2 {
            color: #222;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
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
            margin-top: 10px;
            transition: background 0.3s ease-in-out;
        }
        .btn:hover {
            background-color: #0056b3;
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
        <h2>{{ $property['property_name'] }}</h2>
        <p class="meta">
            <strong>Location:</strong> {{ $property['location'] }}<br>
            <strong>Price:</strong> ₱{{ number_format($property['price'], 2) }}
        </p>
        <p>{{ $property['description'] }}</p>

        <a href="http://localhost:3000/landing/properties" class="btn">View Properties</a>

        <p class="footer">Thank you for staying updated with our latest property listings.</p>

        <p class="unsubscribe">
            If you no longer wish to receive our emails, you can 
            <a href="https://yourwebsite.com/unsubscribe">unsubscribe here</a>.
        </p>
    </div>
</body>
</html>
