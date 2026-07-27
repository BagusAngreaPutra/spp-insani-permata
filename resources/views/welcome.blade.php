<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · Permata Insani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            color: #101828;
            background: #f7f8fa;
            font-family: "Inter", system-ui, sans-serif;
        }
        .account-picker {
            width: min(100%, 380px);
            text-align: center;
        }
        .logo {
            width: 58px;
            height: 58px;
            margin-bottom: 22px;
            border-radius: 50%;
            object-fit: cover;
        }
        h1 {
            margin: 0;
            font-size: 28px;
            line-height: 1.25;
            letter-spacing: -.04em;
        }
        p {
            margin: 9px 0 26px;
            color: #667085;
            font-size: 13px;
        }
        .buttons {
            display: grid;
            gap: 10px;
        }
        .button {
            display: block;
            min-height: 44px;
            padding: 12px 16px;
            color: #344054;
            background: #fff;
            border: 1px solid #d0d5dd;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }
        .button:hover {
            color: #2878f0;
            border-color: #9cc2fa;
        }
        .button.primary {
            color: #fff;
            background: #2878f0;
            border-color: #2878f0;
        }
        .button.primary:hover {
            background: #1768dc;
            border-color: #1768dc;
        }
    </style>
</head>
<body>
    <main class="account-picker">
        <img class="logo" src="{{ asset('images/logo.jpg') }}" alt="Permata Insani">
        <h1>Pilih akun</h1>
        <p>Masuk sesuai jenis akun Anda.</p>
        <div class="buttons">
            <a class="button primary" href="{{ route('siswa.login') }}">Siswa</a>
            <a class="button" href="{{ route('login') }}">Guru</a>
        </div>
    </main>
</body>
</html>
