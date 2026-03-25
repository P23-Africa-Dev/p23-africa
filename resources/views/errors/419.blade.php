<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Session Expired | P23 Africa</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon-new.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">

    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Montserrat", sans-serif;
            background: linear-gradient(135deg, #0a3d43 0%, #0d4036 50%, #591044 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: #ffffff;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(252, 182, 159, 0.4);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(252, 182, 159, 0);
            }
        }

        .error-icon i {
            font-size: 45px;
            color: #591044;
        }

        .error-title {
            font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
            font-size: 2rem;
            color: #0a3d43;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .error-message {
            font-family: "GT Walsheim Con", "Montserrat", sans-serif;
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 35px;
            letter-spacing: 0.3px;
        }

        .refresh-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: #591044;
            color: #ffffff;
            padding: 16px 40px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px -8px rgba(89, 16, 68, 0.5);
            border: none;
            cursor: pointer;
            font-family: "GT Walsheim Con", "Montserrat", sans-serif;
            letter-spacing: 0.5px;
        }

        .refresh-btn:hover {
            background: #6d1854;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -8px rgba(89, 16, 68, 0.6);
            color: #ffffff;
        }

        .refresh-btn:active {
            transform: translateY(0);
        }

        .refresh-btn i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .refresh-btn:hover i {
            transform: rotate(180deg);
        }

        .auto-refresh-text {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #888;
            font-family: "GT Walsheim Con", "Montserrat", sans-serif;
        }

        .countdown {
            font-weight: 700;
            color: #591044;
        }

        .home-link {
            display: inline-block;
            margin-top: 20px;
            color: #0a3d43;
            text-decoration: none;
            font-size: 0.9rem;
            font-family: "GT Walsheim Con", "Montserrat", sans-serif;
            transition: color 0.3s ease;
        }

        .home-link:hover {
            color: #591044;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 40px 25px;
            }

            .error-title {
                font-size: 1.6rem;
            }

            .error-message {
                font-size: 0.9rem;
            }

            .refresh-btn {
                padding: 14px 30px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>

        <h1 class="error-title">Session Expired</h1>

        <p class="error-message">
            Your session has timed out for security reasons.
            Don't worry, just refresh the page to continue where you left off.
        </p>

        <button class="refresh-btn" onclick="window.location.reload();">
            <i class="fa-solid fa-rotate-right"></i>
            Refresh Page
        </button>

        <p class="auto-refresh-text">
            Auto-refreshing in <span class="countdown" id="countdown">10</span> seconds...
        </p>

        <a href="{{ url('/') }}" class="home-link">
            <i class="fa-solid fa-house"></i> Back to Home
        </a>
    </div>

    <script>
        // Auto-refresh countdown
        let seconds = 10;
        const countdownEl = document.getElementById('countdown');

        const interval = setInterval(function() {
            seconds--;
            countdownEl.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(interval);
                window.location.reload();
            }
        }, 1000);

        // Also allow clicking anywhere to refresh
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                window.location.reload();
            }
        });
    </script>
</body>

</html>
