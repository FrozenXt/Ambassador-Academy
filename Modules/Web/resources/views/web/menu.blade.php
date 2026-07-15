<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu — Coming Soon</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Jost:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Jost', sans-serif;
            background: linear-gradient(135deg, #1c1410 0%, #2b1c12 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        .wrap {
            max-width: 560px;
        }

        .icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: rgba(217, 119, 6, .15);
            border: 1px solid rgba(217, 119, 6, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #d97706;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.6rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        p {
            color: rgba(255, 255, 255, .6);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .divider {
            width: 60px;
            height: 2px;
            background: #d97706;
            margin: 0 auto 28px;
        }

        .btn-home {
            display: inline-block;
            padding: 12px 32px;
            border: 1.5px solid #d97706;
            border-radius: 8px;
            color: #d97706;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            letter-spacing: .5px;
            transition: all .2s;
        }

        .btn-home:hover {
            background: #d97706;
            color: #fff;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="icon">
            <i class="fas fa-camera-retro"></i>
        </div>
        <h1>Menu Coming Soon</h1>
        <div class="divider"></div>
        <p>
            We're curating our best taste with you.
            Our menu is on its way — stay tuned.
        </p>
        <a href="{{ route('home') }}" class="btn-home">Back to Home</a>
    </div>
</body>

</html>
