<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sero-Secret</title>
    <style>
        body {
            background-color: #fff;
            font-family: 'Prompt', sans-serif;
            margin: 0;
            padding: 0;
        }
        .login-container {
            width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #E9D4B6;
            border-radius: 25px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #1e4620;
            margin-bottom: 5px;
        }
        h3 {
            color: #4a773e;
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: normal;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            background: #fff;
            border: 1px solid #000;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .input-group i {
            margin-right: 10px;
        }
        .input-group input {
            border: none;
            width: 100%;
            padding: 10px;
            outline: none;
            font-size: 14px;
        }
        .login-btn {
            background-color: #2b5f2f;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 40px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .login-btn:hover {
            background-color: #1e4620;
        }
        .register-link {
            font-size: 14px;
            margin-top: 10px;
        }
        .register-link a {
            color: #4a773e;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #2b5f2f;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<a href="/" class="home-button">🏠 Home</a>

<div class="login-container">
    <h1>Sero-Secret</h1>
    <h3>ผ้าพิมพ์ลายธรรมชาติ ด้วยเทคนิค Eco Print</h3>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group">
            <i>👤</i>
            <input type="text" name="username" placeholder="username" required>
        </div>

        <div class="input-group">
            <i>🔒</i>
            <input type="password" name="password" placeholder="password" required>
        </div>

        <button type="submit" class="login-btn">LOGIN</button>
    </form>

    {{-- <div class="register-link">
        ต้องการสมัครสมาชิกหรือไม่ ? <a href="{{ route('register') }}">สมัครสมาชิก</a>
    </div> --}}
</div>

</body>
</html>