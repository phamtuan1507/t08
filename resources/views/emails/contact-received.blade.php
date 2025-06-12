<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #2c3e50;">New Contact Request</h2>
    <p>A new contact request has been submitted. Details are below:</p>
    <ul style="list-style-type: none; padding: 0;">
        <li><strong>Name:</strong> {{ $contact->name }}</li>
        <li><strong>Email:</strong> {{ $contact->email }}</li>
        <li><strong>Subject:</strong> {{ $contact->subject }}</li>
        <li><strong>Message:</strong> {{ $contact->message }}</li>
    </ul>
    <p style="color: #7f8c8d;">Received on: {{ $contact->created_at }}</p>
</body>
</html>
