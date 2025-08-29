<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CSIRT PALI</title>
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
            <h1 class="login-title">Create Account</h1>
            <p class="login-subtitle">Join CSIRT PALI</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter your first name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter your last name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>
            </div>

            <div class="form-group">
                <label for="organization" class="form-label">Organization</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <input type="text" id="organization" name="organization" class="form-control" placeholder="Enter your organization" required>
                </div>
            </div>

            <div class="form-group">
                <label for="country" class="form-label">Country</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <input type="text" id="country" name="country" class="form-control" placeholder="Enter your country" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <span class="btn-text">Create Account</span>
            </button>
        </form>

        <div class="login-links">
            <p>
                Already have an account? 
                <a href="/login">Sign In</a>
            </p>
        </div>
    </div>
</body>
</html>