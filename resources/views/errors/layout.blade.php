<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | Rafa Kost</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            /* Gradient putih ke biru muda yang simpel */
            background: linear-gradient(to bottom, #ffffff 0%, #e0f2fe 100%);
            color: #1e293b;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
            text-align: center;
        }

        .brand {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 24px;
        }

        .code {
            font-size: clamp(80px, 15vw, 120px);
            font-weight: 700;
            line-height: 1;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .title {
            font-size: clamp(20px, 4vw, 24px);
            font-weight: 500;
            margin-bottom: 16px;
        }

        .description {
            font-size: 16px;
            color: #475569;
            max-width: 420px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        /* Link dibikin simpel cuma teks digarisbawahi */
        .link-beranda {
            color: #0284c7;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            padding-bottom: 2px;
            border-bottom: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .link-beranda:hover {
            color: #0369a1;
            border-bottom-color: #0369a1;
        }
    </style>
</head>

<body>
    <div class="brand">Rafa Kost</div>
    <h1 class="code">@yield('code')</h1>
    <h2 class="title">@yield('title')</h2>
    <p class="description">@yield('message')</p>
    
    <a href="https://rafakost.biz.id" class="link-beranda">Kembali ke Beranda &rarr;</a>
</body>
</html>