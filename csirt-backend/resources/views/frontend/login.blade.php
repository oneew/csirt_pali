<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CSIRT PALI</title>
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
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">CSIRT PALI</p>
        </div>
            <!-- <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
            </div> -->
        <form id="loginForm" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="Enter your email address"
                           required>
                </div>
                @error('email')
                    <div class="invalid-feedback" style="display: block;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Enter your password"
                           required>
                </div>
                @error('password')
                    <div class="invalid-feedback" style="display: block;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" class="checkbox" value="1">
                <label for="remember" class="checkbox-label">Remember me</label>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <span class="btn-text">Sign In</span>
                <div class="loading" style="display: none;"></div>
            </button>
        </form>

        <div class="login-links">
            <p>
                <a href="/forgot-password">Forgot your password?</a>
            </p>
            <p style="margin-top: 1rem;">
                Don't have an account? 
                <a href="/register">Create Account</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            const loading = btn.querySelector('.loading');
            
            btn.disabled = true;
            btnText.style.display = 'none';
            loading.style.display = 'inline-block';
            
            // Add a minimum delay to show loading state
            setTimeout(() => {
                // Form will submit naturally
            }, 500);
        });

        // Add input animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>