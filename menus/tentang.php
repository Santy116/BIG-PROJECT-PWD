<?php
require_once '../config.php';
require_once '../includes/header.php';
?>
<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/menu.css">
<style>
    /* About Us Styles */
.about-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    margin-bottom: 56px;
    align-items: center;
    background: linear-gradient(120deg, #f8f9fc 60%, #e3e9f7 100%);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(76,99,255,0.07), 0 1.5px 4px rgba(60,80,120,0.04);
    padding: 40px 32px;
}

.about-image {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
}
.about-image img {
    width: 100%;
    max-width: 420px;
    border-radius: 18px;
    display: block;
    box-shadow: 0 4px 24px rgba(76,99,255,0.13);
    border: 4px solid #fff;
    background: #fff;
    transition: transform 0.18s, box-shadow 0.18s;
}
.about-image img:hover {
    transform: scale(1.03) rotate(-1deg);
    box-shadow: 0 8px 32px rgba(76,99,255,0.18);
}

.about-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 18px;
}
.about-content h1, .about-content h2 {
    margin-top: 0;
    color: #4e73df;
    font-size: 2.2rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-shadow: 0 2px 8px rgba(44,62,80,0.10);
}
.about-content p {
    font-size: 1.18rem;
    color: #222;
    line-height: 1.7;
    margin-bottom: 0;
    letter-spacing: 0.2px;
}
.about-content .about-highlight {
    background: linear-gradient(90deg, #ff5e62 0%, #ff9966 100%);
    color: #fff;
    padding: 8px 18px;
    border-radius: 12px;
    font-weight: 600;
    display: inline-block;
    margin-top: 10px;
    box-shadow: 0 2px 8px rgba(255,94,98,0.10);
    font-size: 1.05rem;
    letter-spacing: 0.5px;
}

.team-section {
    margin-top: 64px;
    text-align: center;
}
.team-section h2 {
    color: #4e73df;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 18px;
    letter-spacing: 1px;
    text-shadow: 0 2px 8px rgba(44,62,80,0.10);
}
.team-section p {
    color: #555;
    font-size: 1.08rem;
    margin-bottom: 32px;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 32px;
    margin-top: 20px;
}

.team-member {
    background: linear-gradient(120deg, #fff 80%, #e3e9f7 100%);
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(76,99,255,0.07);
    padding: 28px 18px 22px 18px;
    text-align: center;
    transition: box-shadow 0.18s, transform 0.18s;
    border: 2px solid #e3e9f7;
    position: relative;
}
.team-member:hover {
    box-shadow: 0 8px 32px rgba(76,99,255,0.13), 0 1.5px 4px rgba(60,80,120,0.09);
    transform: translateY(-4px) scale(1.025);
    border-color: #4e73df;
    background: #f8f9fc;
}

.team-member img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 18px;
    border: 4px solid #fff;
    box-shadow: 0 2px 8px rgba(76,99,255,0.13);
    background: #fff;
    transition: transform 0.18s, box-shadow 0.18s;
}
.team-member:hover img {
    transform: scale(1.07) rotate(-2deg);
    box-shadow: 0 6px 24px rgba(76,99,255,0.18);
}

.team-member h3 {
    margin: 0 0 6px 0;
    font-size: 1.18rem;
    color: #4e73df;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.team-member .role {
    color: #ff5e62;
    font-size: 1.02rem;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    letter-spacing: 0.3px;
}
.team-member p {
    color: #444;
    font-size: 1rem;
    margin: 0;
    line-height: 1.5;
}

.team-member .social-links {
    margin-top: 14px;
    display: flex;
    justify-content: center;
    gap: 14px;
}
.team-member .social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #e3e9f7;
    color: #4e73df;
    font-size: 1.18rem;
    transition: background 0.18s, color 0.18s, transform 0.18s;
    text-decoration: none;
    box-shadow: 0 1px 4px rgba(76,99,255,0.07);
}
.team-member .social-links a:hover {
    background: linear-gradient(90deg, #4f8cff 0%, #6c63ff 100%);
    color: #fff;
    transform: scale(1.12);
}

@media (max-width: 900px) {
    .about-section {
        grid-template-columns: 1fr;
        padding: 24px 10px;
        gap: 24px;
    }
    .about-image img {
        max-width: 100%;
    }
    .team-grid {
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }
}
@media (max-width: 600px) {
    .about-section {
        padding: 10px 2px;
        border-radius: 10px;
    }
    .about-image img {
        border-radius: 10px;
    }
    .team-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    .team-member {
        padding: 16px 6px 12px 6px;
        border-radius: 10px;
    }
    .team-member img {
        width: 80px;
        height: 80px;
    }
}
.walbay-creator {
    background-color: #007bff; /* Warna biru lembut */
    color: #fff; /* Warna teks putih */
    padding: 10px 20px; /* Ruang di dalam elemen */
    border-radius: 8px; /* Sudut melengkung */
    font-size: 1rem; /* Ukuran teks */
    font-weight: 600; /* Teks tebal */
    text-align: center; /* Teks rata tengah */
    display: inline-block; /* Agar elemen tidak memenuhi lebar penuh */
    margin-top: 15px; /* Jarak atas */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Bayangan lembut */
}
</style>
<div class="container">
    <h1>About WalBayExpress</h1>
    <div class="about-section">
        <div class="about-content">
            <h2>Our Story</h2>
            <p>Founded in 2023, WalBayExpress combines the best features of Walmart, eBay, and AliExpress to create a unique shopping experience. Our mission is to provide customers with a wide selection of products at competitive prices with fast and reliable shipping.</p>
            <h2>Our Vision</h2>
            <p>To become the world's most customer-centric e-commerce platform, where customers can find and discover anything they want to buy online.</p>
            
            <h2>Our Values</h2>
            <ul>
                <li><strong>Customer Obsession:</strong> We start with the customer and work backwards.</li>
                <li><strong>Innovation:</strong> We embrace new ideas and technologies.</li>
                <li><strong>Quality:</strong> We provide high-quality products and services.</li>
                <li><strong>Integrity:</strong> We do the right thing, always.</li>
            </ul>
        </div>
        
        <div class="about-image">
            <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/logo-walbayexpress.png" alt="About WalBayExpress">
        </div>
    </div>

    <div class="team-section" style="margin-top: 36px; margin-bottom: 24px;">
        <h2>Creator</h2>
        <div class="team-grid" style="justify-content: center;">
            <div class="team-member" style="margin: 0 auto;">
            <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/santy.jpg" alt="Santy Rahmawati Usman">
            <h3>Santy Rahmawati Usman</h3>
            <span class="role" style="color: #444; font-weight: normal;">Creator</span>
            </div>
        </div>
    </div>

    <div class="team-section">
        <h2>Our Team</h2>
        <div class="team-grid">
            <div class="team-member">
                <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/Gorgeous smile of Alexis Petit.jpg" alt="Team Member">
                <h3>John Smith</h3>
                <p>CEO & Founder</p>
            </div>
            <div class="team-member">
                <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/Men's Wavy Haircuts 2024_ Styles for Every Length and Fashion.jpg" alt="Team Member">
                <h3>Sarah Johnson</h3>
                <p>Head of Operations</p>
            </div>
            <div class="team-member">
                <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/Nashville's Luxury Headshot & Branding Photographer __ Corporate Photos _ Tausha Dickinson Photography.jpg" alt="Team Member">
                <h3>Michael Chen</h3>
                <p>Technology Director</p>
            </div>
            <div class="team-member">
                <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/Portrait Of a Confident Young Smart Looking Man _ Premium AI-generated image.jpg" alt="Team Member">
                <h3>Emily Rodriguez</h3>
                <p>Marketing Manager</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>