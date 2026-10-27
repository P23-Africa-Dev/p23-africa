<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We'll be right back | P23 Africa</title>
    @isset($retryAfter)
        <meta http-equiv="refresh" content="{{ (int) $retryAfter }}">
    @endisset
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: #f4f7f6;
            color: #14312c;
            font-family: Georgia, "Times New Roman", serif;
        }
        .card {
            width: min(560px, 100%);
            background: #fff;
            border-radius: 18px;
            padding: 2.5rem 2rem;
            box-shadow: 0 16px 40px rgba(13, 64, 54, 0.08);
        }
        .mark {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e7f6f1;
            color: #0d4036;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", sans-serif;
            font-weight: 700;
        }
        h1 {
            margin: 1.25rem 0 0.75rem;
            font-size: 2rem;
            line-height: 1.2;
            font-weight: 600;
        }
        p {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            font-size: 1.05rem;
            line-height: 1.6;
            color: #3d524c;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.75rem;
        }
        a {
            font-family: "Segoe UI", sans-serif;
            text-decoration: none;
            border-radius: 999px;
            padding: 0.75rem 1.2rem;
            font-weight: 600;
        }
        .home {
            background: #0d4036;
            color: #fff;
        }
        .again {
            color: #0d4036;
            border: 1px solid #b7d4cb;
        }
        .quiet {
            margin-top: 1.5rem;
            font-size: 0.92rem;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="mark">P23</div>
        <h1>We'll be right back</h1>
        <p>P23 Africa is being updated. Please try again in a few minutes. You can return to the homepage and come back when you're ready.</p>
        <div class="actions">
            <a class="home" href="/">Back to homepage</a>
            <a class="again" href="" onclick="location.reload(); return false;">Try again</a>
        </div>
        <p class="quiet">Thank you for your patience.</p>
    </main>
</body>
</html>
