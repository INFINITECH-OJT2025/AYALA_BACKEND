<!DOCTYPE html>
<html>
<head>
    <title>Property Approved</title>
</head>
<body>
    <h2>Congratulations, Your Property is Now Live!</h2>
    <p>Hello {{ $property->first_name }},</p>
    <p>Your property titled "<strong>{{ $property->property_name }}</strong>" has been approved and is now listed on our website.</p>
    <p>View your listing: <a href="{{ url('/properties/' . $property->id) }}">Click here</a></p>
    <p>Thank you for choosing our platform!</p>
</body>
</html>
