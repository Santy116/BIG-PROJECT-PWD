<?php
// /C:/xampp/htdocs/PWDTUBES_WalBayExpress/services/make-money-with-us.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Money With Us | WalBayExpress</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1976d2;
            --secondary: #ffffff;
            --accent: #e3f0ff;
            --text: #263238;
            --shadow: 0 4px 24px rgba(25, 118, 210, 0.10);
        }
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(135deg, #e3f0ff 0%, #ffffff 100%);
            margin: 0;
            padding: 0;
            color: var(--text);
        }
        .header {
            background: var(--primary);
            color: var(--secondary);
            padding: 48px 0 32px 0;
            text-align: center;
            border-radius: 0 0 48px 48px;
            box-shadow: var(--shadow);
            position: relative;
        }
        .header h1 {
            font-size: 2.8rem;
            margin: 0 0 12px 0;
            letter-spacing: 1px;
        }
        .header p {
            font-size: 1.2rem;
            margin: 0;
            opacity: 0.92;
        }
        .container {
            max-width: 1100px;
            margin: -60px auto 40px auto;
            background: var(--secondary);
            padding: 40px 32px 32px 32px;
            border-radius: 18px;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 2;
        }
        .opportunities {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            margin-top: 24px;
        }
        .card {
            flex: 1 1 340px;
            background: var(--accent);
            border-radius: 14px;
            padding: 32px 24px 28px 24px;
            box-shadow: 0 2px 12px rgba(25, 118, 210, 0.07);
            transition: transform 0.18s, box-shadow 0.18s;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-8px) scale(1.025);
            box-shadow: 0 8px 32px rgba(25, 118, 210, 0.18);
        }
        .card .icon {
            font-size: 2.6rem;
            margin-bottom: 18px;
            color: var(--primary);
            background: #fff;
            border-radius: 50%;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.08);
        }
        .card h2 {
            color: var(--primary);
            margin: 0 0 10px 0;
            font-size: 1.35rem;
            font-weight: 700;
        }
        .card p {
            color: #35506b;
            font-size: 1.05rem;
            margin-bottom: 18px;
        }
        .card .cta {
            margin-top: auto;
            background: var(--primary);
            color: var(--secondary);
            border: none;
            border-radius: 6px;
            padding: 10px 22px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.10);
        }
        .card .cta:hover {
            background: #1256a6;
        }
        .steps {
            margin: 48px 0 0 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: space-between;
        }
        .step {
            flex: 1 1 220px;
            background: #f7fbff;
            border-left: 5px solid var(--primary);
            border-radius: 10px;
            padding: 22px 18px 18px 22px;
            box-shadow: 0 1px 6px rgba(25, 118, 210, 0.06);
            min-width: 180px;
        }
        .step-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 6px;
        }
        .step-desc {
            font-size: 0.98rem;
            color: #35506b;
        }
        @media (max-width: 900px) {
            .container { padding: 28px 8px 18px 8px; }
            .opportunities { gap: 18px; }
            .steps { gap: 18px; }
        }
        @media (max-width: 700px) {
            .opportunities, .steps { flex-direction: column; }
            .container { margin: -40px 8px 24px 8px; }
        }
        /* Decorative waves */
        .wave {
            position: absolute;
            left: 0; right: 0; bottom: -1px;
            width: 100%;
            height: 60px;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div style="text-align:center; margin-top:32px; margin-bottom:-24px;">
        <img src="/PWDTUBES_WalBayExpress//assets/img/tentang/logo-walbayexpress.png" alt="WalBayExpress Logo" style="height:64px; width:auto;">
        <div style="margin-top:12px; font-size:1.1rem; color:#1976d2; font-weight:600;">
            Bersama WalBayExpress, raih peluang dan penghasilan tanpa batas!
        </div>
    </div>
    <div class="header">
        <h1>Make Money With Us</h1>
        <p>Empower your business and income with WalBayExpress. Choose your path to success in our blue & white world.</p>
        <svg class="wave" viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="#fff" fill-opacity="1" d="M0,32L80,37.3C160,43,320,53,480,53.3C640,53,800,43,960,37.3C1120,32,1280,32,1360,32L1440,32L1440,60L1360,60C1280,60,1120,60,960,60C800,60,640,60,480,60C320,60,160,60,80,60L0,60Z"></path>
        </svg>
    </div>
    <div class="container">
        <div class="opportunities">
            <div class="card">
                <span class="icon">🛒</span>
                <h2>Sell on WalBayExpress</h2>
                <p>Reach millions of buyers by listing your products on our trusted marketplace. Enjoy seamless onboarding, secure transactions, and powerful seller tools to grow your business.</p>
                <a href="#" class="cta">Start Selling</a>
            </div>
            <div class="card">
                <span class="icon">🤝</span>
                <h2>Become an Affiliate</h2>
                <p>Promote WalBayExpress and earn commissions for every successful referral. Share your unique links on your website, blog, or social media and track your earnings in real-time.</p>
                <a href="#" class="cta">Join Affiliate</a>
            </div>
            <div class="card">
                <span class="icon">📢</span>
                <h2>Advertise Your Products</h2>
                <p>Boost your visibility and sales with our targeted advertising solutions. Reach the right audience and maximize your ROI with easy-to-use campaign tools.</p>
                <a href="#" class="cta">Advertise Now</a>
            </div>
            <div class="card">
                <span class="icon">🚚</span>
                <h2>Become a Logistics Partner</h2>
                <p>Join our logistics network to provide reliable delivery services. Help us ensure fast, safe, and efficient deliveries to customers across the region.</p>
                <a href="#" class="cta">Partner with Us</a>
            </div>
        </div>
        <ul class="steps">
            <li class="step">
                <div class="step-title">1. Register</div>
                <div class="step-desc">Sign up for your chosen opportunity with a simple online form.</div>
            </li>
            <li class="step">
                <div class="step-title">2. Get Verified</div>
                <div class="step-desc">Complete verification to ensure a secure and trusted partnership.</div>
            </li>
            <li class="step">
                <div class="step-title">3. Start Earning</div>
                <div class="step-desc">Access your dashboard, manage your activities, and watch your income grow!</div>
            </li>
        </ul>
    </div>
</body>
</html>