<!DOCTYPE html>
<html lang="en" class="dark-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SmartLearn LMS</title>
    <meta name="description" content="Register for SmartLearn LMS">
    
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
            max-width: 500px;
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

        .auth-form-label .auth-required {
            color: var(--danger-color);
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

        .auth-form-input.error {
            border-color: var(--danger-color);
        }

        .auth-form-error {
            color: var(--danger-color);
            font-size: 12px;
            margin-top: 5px;
        }

        .auth-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .auth-form-row {
                grid-template-columns: 1fr;
            }
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

        select.auth-form-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
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
                    <span>SmartLearn</span>
                </a>
            </div>

            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="auth-title">Create Account</h1>
                    <p class="auth-subtitle">Sign up to start learning today</p>
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

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="auth-form-group">
                        <label class="auth-form-label">
                            Full Name <span class="auth-required">*</span>
                        </label>
                        <div class="auth-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" 
                                   class="auth-form-input @error('name') error @enderror" 
                                   name="name" 
                                   placeholder="Enter your full name" 
                                   value="{{ old('name') }}" 
                                   required>
                        </div>
                        @error('name')
                            <div class="auth-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-form-label">
                            Email Address <span class="auth-required">*</span>
                        </label>
                        <div class="auth-input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" 
                                   class="auth-form-input @error('email') error @enderror" 
                                   name="email" 
                                   placeholder="Enter your email" 
                                   value="{{ old('email') }}" 
                                   required>
                        </div>
                        @error('email')
                            <div class="auth-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label class="auth-form-label">
                                Password <span class="auth-required">*</span>
                            </label>
                            <div class="auth-input-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" 
                                       class="auth-form-input @error('password') error @enderror" 
                                       name="password" 
                                       placeholder="Create password" 
                                       required>
                            </div>
                            @error('password')
                                <div class="auth-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="auth-form-group">
                            <label class="auth-form-label">
                                Confirm Password <span class="auth-required">*</span>
                            </label>
                            <div class="auth-input-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" 
                                       class="auth-form-input" 
                                       name="password_confirmation" 
                                       placeholder="Confirm password" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-form-label">
                            I want to join as <span class="auth-required">*</span>
                        </label>
                        <select name="role" class="auth-form-input @error('role') error @enderror" required>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        </select>
                        @error('role')
                            <div class="auth-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="auth-submit-btn">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <div class="auth-footer">
                    <p class="auth-footer-text">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="auth-footer-link">Login Now</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
