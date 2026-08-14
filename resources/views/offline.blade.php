<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're offline — Our House Help</title>
    {{-- Deliberately self-contained: this page is served by the service worker
         when the network is unavailable, so it cannot depend on fetching CSS. --}}
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f7fa;
            color: #2A2D5A;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            padding: 38px 30px;
            max-width: 380px;
            width: 100%;
            text-align: center;
            box-shadow: 0 2px 20px rgba(42, 45, 90, .09);
        }
        img { width: 72px; height: 72px; margin-bottom: 18px; }
        h1 { font-size: 21px; margin: 0 0 10px; }
        p { color: #6c757d; font-size: 15px; line-height: 1.6; margin: 0 0 22px; }
        button {
            background: #2A2D5A;
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 12px 26px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: #3a3e75; }
        .hint { margin-top: 18px; font-size: 13px; color: #9599b3; }
    </style>
</head>
<body>
    <div class="card">
        <img src="/icons/icon-192.png" alt="Our House Help">
        <h1>You're offline</h1>
        <p>
            We can't reach the network right now. Check your data or Wi-Fi
            connection and try again — your booking details are safe.
        </p>
        <button onclick="window.location.reload()">Try again</button>
        <div class="hint">Need help urgently? Call 0812 623 7436</div>
    </div>
</body>
</html>
