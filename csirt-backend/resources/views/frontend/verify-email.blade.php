<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - CSIRT PALI</title>
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
            <h1 class="login-title">Verify Your Email</h1>
            <p class="login-subtitle">Please check your email for verification instructions</p>
        </div>

        <div class="verification-message">
            <div class="alert alert-info" style="background: #e3f2fd; border: 1px solid #2196f3; color: #0d47a1; padding: 1rem; border-radius: 8px; margin: 2rem 0;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                We've sent a verification link to your email address. Please click the link to verify your account.
            </div>
        </div>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            
            <button type="submit" class="btn-login">
                <span class="btn-text">Resend Verification Email</span>
            </button>
        </form>

        <div class="login-links">
            <p>
                Already verified? 
                <a href="/login">Sign In</a>
            </p>
        </div>
    </div>
</body>
</html>