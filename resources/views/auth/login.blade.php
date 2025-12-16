<!DOCTYPE html>
<html lang="en" class="dark-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartLearn LMS</title>
    <meta name="description" content="Login to SmartLearn LMS">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/adomx.css') }}">
    
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-bg);
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-logo a {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 28px;
            font-weight: 700;
        }

        .auth-logo i {
            font-size: 32px;
            color: var(--primary-color);
        }

        .auth-card {
            background: var(--dark-bg-light);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: var(--text-muted);
        }

        .auth-form-group {
            margin-bottom: 20px;
        }

        .auth-form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .auth-form-input {
            width: 100%;
            padding: 14px 16px;
            background: var(--dark-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .auth-form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .auth-form-input::placeholder {
            color: var(--text-muted);
        }

        .auth-checkbox-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .auth-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .auth-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .auth-checkbox label {
            font-size: 14px;
            color: var(--text-secondary);
            cursor: pointer;
            margin: 0;
        }

        .auth-forgot-link {
            font-size: 14px;
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .auth-forgot-link:hover {
            color: #4338ca;
        }

        .auth-submit-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .auth-submit-btn:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
        }

        .auth-divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .auth-divider span {
            position: relative;
            background: var(--dark-bg-light);
            padding: 0 15px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        .auth-footer-text {
            font-size: 14px;
            color: var(--text-muted);
        }

        .auth-footer-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-footer-link:hover {
            color: #4338ca;
        }

        .auth-alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .auth-alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }

        .auth-alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .auth-input-icon {
            position: relative;
        }

        .auth-input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }

        .auth-input-icon .auth-form-input {
            padding-left: 45px;
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
        <div class="auth-container">
            <div class="auth-logo">
                <a href="{{ route('home') }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>LMS</span>
                </a>
            </div>

            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="auth-title">Welcome Back</h1>
                    <p class="auth-subtitle">Login to your account to continue</p>
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

                @if (session('success'))
                    <div class="auth-alert auth-alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="auth-form-group">
                        <label class="auth-form-label">Email Address</label>
                        <div class="auth-input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" 
                                   class="auth-form-input" 
                                   name="email" 
                                   placeholder="Enter your email" 
                                   value="{{ old('email') }}" 
                                   required>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-form-label">Password</label>
                        <div class="auth-input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   class="auth-form-input" 
                                   name="password" 
                                   placeholder="Enter your password" 
                                   required>
                        </div>
                    </div>

                    <div class="auth-checkbox-wrapper">
                        <div class="auth-checkbox">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Remember Me</label>
                        </div>
                        <a href="#" class="auth-forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="auth-submit-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </button>
                </form>

                <div class="auth-footer">
                    <p class="auth-footer-text">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="auth-footer-link">Register Now</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
