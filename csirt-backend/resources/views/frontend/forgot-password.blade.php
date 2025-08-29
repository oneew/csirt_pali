<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CSIRT PALI</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-home">
        <a href="/">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <img src="{{ asset('frontend/images/Logo.png') }}" alt="CSIRT PALI Logo">
            </div>
            <h1 class="login-title">Reset Password</h1>
            <p class="login-subtitle">Enter your email to receive reset instructions</p>
        </div>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <span class="btn-text">Send Reset Link</span>
            </button>
        </form>

        <div class="login-links">
            <p>
                Remember your password? 
                <a href="/login">Sign In</a>
            </p>
            <p style="margin-top: 1rem;">
                Don't have an account? 
                <a href="/register">Create Account</a>
            </p>
        </div>
    </div>
</body>
</html>