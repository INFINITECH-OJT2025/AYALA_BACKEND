<!DOCTYPE html>
<html>
<head>
    <title>New Property Available</title>
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
        <h2>{{ $property['property_name'] }}</h2>
        <p class="meta">
            <strong>Location:</strong> {{ $property['location'] }} |
            <strong>Price:</strong> ₱{{ number_format($property['price'], 2) }}
        </p>
        <p>{{ $property['description'] }}</p>

        <a href="http://localhost:3000/landing/properties" class="btn">View Properties</a>

        <p class="footer">Thank you for staying updated with our latest property listings.</p>
    </div>
</body>
</html>
