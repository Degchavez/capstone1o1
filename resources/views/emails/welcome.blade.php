<div>
    <h1>Welcome, {{ $user->complete_name }}!</h1>
    <p>We are excited to have you as part of our system. Below is your account information:</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $randomPassword }}</p>
    <p>Please change your password once you log in.</p>
</div>
