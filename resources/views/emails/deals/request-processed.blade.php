<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nitro Request Update</title>
</head>
<body>
<h2>Hello,</h2>

<p>
    Your {{ strtolower($requestLabel) }} has been
    <strong>{{ strtolower($statusLabel) }}</strong>.
</p>

<p>Deal ID: {{ $deal->id }}</p>

<p>Request type: {{ $requestLabel }}</p>

<p>Status: {{ $statusLabel }}</p>

<p>Please log in to your Nitro account to view your deal details.</p>

<p>
    Regards,<br>
    Nitro Team
</p>
</body>
</html>
