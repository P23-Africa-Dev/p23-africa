<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Challenge Submission</title>
</head>
<body>
    <h2>Quiz Result: {{ $label }}</h2>

    <p><strong>Challenge 1:</strong> {{ $challenge1 ?: 'Not provided' }}</p>
    <p><strong>Challenge 2:</strong> {{ $challenge2 ?: 'Not provided' }}</p>
    <p><strong>Challenge 3:</strong> {{ $challenge3 ?: 'Not provided' }}</p>

    <br>
    <p><strong>User Name:</strong> {{ $name }}</p>
    <p><strong>User Email:</strong> <a href="mailto:{{$email}}">{{ $email ?? 'Not provided' }}</a> </p>
</body>
</html>
