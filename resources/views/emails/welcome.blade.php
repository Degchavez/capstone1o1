<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
        }
        .credentials {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to City Veterinary's Office</h1>
    </div>
    
    <div class="content">
        <h2>Hello, {{ $user->complete_name }}!</h2>
        
        <p>Thank you for registering with the City Veterinary's Office of Valencia City. Your account has been successfully created and is now ready for use.</p>

        <div class="credentials">
            <h3>Your Login Credentials</h3>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Temporary Password:</strong> {{ $randomPassword }}</p>
        </div>

        <p><strong>Important Security Notice:</strong><br>
        For your security, we strongly recommend changing your password after your first login.</p>

        <center>
            <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
        </center>

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

        <div class="footer">
            <p>This is an automated message, please do not reply directly to this email.</p>
            <p>&copy; {{ date('Y') }} City Veterinary's Office, Valencia City. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
