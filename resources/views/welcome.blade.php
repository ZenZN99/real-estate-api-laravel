<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hello Laravel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ff2d20, #1a1a1a); 
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .card {
            background: rgba(26, 26, 26, 0.75); 
            backdrop-filter: blur(15px);
            padding: 50px;
            border-radius: 24px;
            text-align: center;
            width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        h1 {
            font-size: 42px;
            margin: 10px 0;
            font-weight: 800;
            color: #ff2d20; 
        }

        p {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.6;
            margin-bottom: 25px;
            color: #f5f5f5; 
        }

        .badge {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 999px;
            background: #ff2d20;
            font-weight: 600;
            font-size: 14px;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="card">
        <img
            class="logo"
            src="https://laravel.com/img/logomark.min.svg"
            alt="Laravel Logo"
        />
        <h1>Hello Laravel</h1>
        <p>
            Welcome to your Laravel backend.<br>
            Clean architecture, scalable design,<br>
            and production-ready APIs.
        </p>
        <div class="badge">Laravel • PHP • Backend</div>
    </div>
</body>
</html>
