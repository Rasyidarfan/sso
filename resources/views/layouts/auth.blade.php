<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Single Sign-On BPS') }}</title>
    <link rel="shortcut icon" href="{{ asset('icon.ico') }}" type="image/x-icon">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: white;
            width: 100%;
            max-width: 1000px;
            min-height: 400px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
        }

        .left-side {
            background: #0099dd;
            flex: 1;
            padding: 40px;
            position: relative;
            color: white;
            overflow: hidden;
        }

        .left-side::after {
            content: '';
            position: absolute;
            right: -100px;
            top: 0;
            height: 100%;
            width: 200px;
            background: white;
            transform: skewX(-10deg);
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo img {
            height: 40px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        .right-side {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .sign-in-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .sign-in-title {
            color: #333;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .sign-in-subtitle {
            color: #0099dd;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #0099dd;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #0099dd;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background: #0088cc;
        }

        .remember-me {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            margin-left: 15px;
            display: block;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 20px;
            }

            .left-side::after {
                display: none;
            }

            .left-side {
                padding: 30px;
            }

            .right-side {
                padding: 30px;
            }
            
            .docs-link {
                bottom: 15px !important;
                right: 15px !important;
            }
        }
        
        /* Link dokumentasi di pojok kanan bawah */
        .docs-link {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #0099dd;
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 153, 221, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .docs-link:hover {
            background: #0088cc;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 153, 221, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .docs-link i {
            font-size: 16px;
        }
    </style>
</head>
<body>
    @yield('content')
    
    <!-- Link dokumentasi di pojok kanan bawah -->
    <a href="{{ route('api.docs') }}" class="docs-link" title="Buka Dokumentasi API tanpa login">
        <i class="fas fa-book"></i>
        <span>Dokumentasi API</span>
    </a>
</body>
</html>
