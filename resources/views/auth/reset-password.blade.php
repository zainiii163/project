<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SmartLearn LMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 450px;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-logo a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
            font-size: 28px;
            font-weight: bold;
        }

        .auth-logo i {
            font-size: 32px;
        }

        .auth-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: #666;
            font-size: 14px;
        }

        .auth-alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .auth-alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .auth-form-group {
            margin-bottom: 20px;
        }

        .auth-form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .auth-input-icon {
            position: relative;
        }

        .auth-input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .auth-form-input {
            width: 100%;
            padding: 12px 16px 12px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .auth-form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            color: #666;
        }

        .password-strength-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .password-strength-fill {
            height: 100%;
            transition: all 0.3s;
            border-radius: 2px;
        }

        .password-strength-weak { background: #ef4444; width: 33%; }
        .password-strength-medium { background: #f59e0b; width: 66%; }
        .password-strength-strong { background: #10b981; width: 100%; }

        .auth-submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .auth-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }

        .auth-footer-text {
            color: #666;
            font-size: 14px;
        }

        .auth-footer-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .auth-card {
                padding: 30px 20px;
            }

            .auth-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-logo">
            <a href="{{ route('home') }}">
                <i class="fas fa-graduation-cap"></i>
                <span>SmartLearn</span>
            </a>
        </div>

        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle">Enter your new password below</p>
            </div>

            @if ($errors->any())
                <div class="auth-alert auth-alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="auth-form-group">
                    <label for="email" class="auth-form-label">Email Address</label>
                    <div class="auth-input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="auth-form-input" 
                               value="{{ $email ?? old('email') }}" 
                               required 
                               readonly>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-form-label">New Password</label>
                    <div class="auth-input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="auth-form-input" 
                               placeholder="Enter new password" 
                               required 
                               minlength="8"
                               autofocus>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar">
                            <div class="password-strength-fill" id="strengthBar"></div>
                        </div>
                        <small>Password must be at least 8 characters long</small>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="password_confirmation" class="auth-form-label">Confirm Password</label>
                    <div class="auth-input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="auth-form-input" 
                               placeholder="Confirm new password" 
                               required>
                    </div>
                </div>

                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-key"></i>
                    Reset Password
                </button>
            </form>

            <div class="auth-footer">
                <p class="auth-footer-text">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="auth-footer-link">Back to Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            strengthBar.className = 'password-strength-fill';
            if (strength <= 2) {
                strengthBar.classList.add('password-strength-weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('password-strength-medium');
            } else {
                strengthBar.classList.add('password-strength-strong');
            }
        });
    </script>
</body>
</html>

