<!DOCTYPE html>
<html>
<head>
    <title>Test Email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f8f8; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .footer { font-size: 0.9em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Test Email</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>This is a test email sent from your Laravel Breeze election system using Gmail SMTP.</p>
            <p>Thank you for testing!</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
