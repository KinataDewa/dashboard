<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f4fc; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .wrap { text-align: center; max-width: 420px; padding: 40px; }
        .code { font-family: 'Space Mono', monospace; font-size: 80px; font-weight: 700; color: #e8334a; line-height: 1; margin-bottom: 16px; }
        h1 { font-size: 22px; font-weight: 800; color: #0b1a35; margin-bottom: 10px; }
        p { font-size: 14px; color: #8da3c0; line-height: 1.7; margin-bottom: 28px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; background: #0b1a35; color: #fff; border-radius: 12px; padding: 12px 24px; font-size: 14px; font-weight: 700; text-decoration: none; transition: all .2s; }
        .btn:hover { background: #1c3260; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="code">403</div>
    <h1>Akses Ditolak</h1>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini. Pastikan Anda login dengan akun yang sesuai dengan role halaman tersebut.</p>
    <a href="{{ url('/') }}" class="btn">← Kembali ke Dashboard</a>
</div>
</body>
</html>