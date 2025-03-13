<!DOCTYPE html>
<html>
<head>
    <title>Property Rejected</title>
</head>
<body>
    <h2>Your Property Listing was Rejected</h2>
    <p>Hello {{ $property->first_name }},</p>
    <p>Unfortunately, your property titled "<strong>{{ $property->property_name }}</strong>" was rejected.</p>
    <p><strong>Reason:</strong> {{ $reason }}</p>
    <p>You may edit and resubmit your listing.</p>
</body>
</html>
