<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | P23 Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #378575 0%, #0d4036 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 90%;
            margin: 2rem;
        }

        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
            animation: shake 1.5s ease-in-out infinite;
        }

        @keyframes shake {

            0%,
            100% {
                transform: rotate(0deg);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: rotate(-8deg);
            }

            20%,
            40%,
            60%,
            80% {
                transform: rotate(8deg);
            }
        }

        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-dashboard {
            background: linear-gradient(135deg, #378575 0%, #0d4036 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-dashboard:active {
            transform: translateY(-1px);
        }

        .btn-retry {
            background: transparent;
            border: 2px solid #378575;
            color: #378575;
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
        }

        .btn-retry:hover {
            background: #378575;
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #888;
        }

        .logo img {
            width: 60px;
            height: 40px;
        }

        @media (max-width: 768px) {
            .error-card {
                padding: 2rem;
                margin: 1rem;
            }

            .error-code {
                font-size: 3rem;
            }

            .error-title {
                font-size: 1.3rem;
            }

            .error-message {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <i class="fas fa-server"></i>
            </div>

            <div class="error-code">500</div>

            <h1 class="error-title">Internal Server Error</h1>

            <p class="error-message">
                Something went wrong on our end. We're already working to fix it.
                Please try again in a few moments.
            </p>

            <div class="actions">
                <a href="javascript:location.reload()" class="btn-retry">
                    <i class="fas fa-redo"></i>
                    Try Again
                </a>

                @auth
                    @if (auth()->user()->hasRole(['admin', 'supervisor', 'staff']))
                        <a href="{{ orgRoute('admin.dashboard') }}" class="btn-dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            Back to Dashboard
                        </a>
                    @else
                        <a href="{{ url('/') }}" class="btn-dashboard">
                            <i class="fas fa-home"></i>
                            Back to Home
                        </a>
                    @endif
                @else
                    <a href="{{ url('/') }}" class="btn-dashboard">
                        <i class="fas fa-home"></i>
                        Back to Home
                    </a>
                @endauth
            </div>

            <div class="footer-text">
                If this problem persists, please contact your administrator.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
