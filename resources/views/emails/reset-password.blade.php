<!DOCTYPE html>
<html>
<head>
    <title>Your Password Has Been Reset</title>
</head>
<body>
    <h1>Password Reset</h1>
    <p>Hello {{ $user->complete_name }},</p>
    <p>Your password has been successfully reset. Here are your new login details:</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>New Password:</strong> {{ $password }}</p>
    <p>We recommend changing your password after logging in to ensure the security of your account.</p>
    <p>Best regards,</p>
    <p>The Support Team</p>
</body>
</html>
