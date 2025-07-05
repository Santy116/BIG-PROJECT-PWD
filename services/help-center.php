<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Help Center - Walbayexpress</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
:root {
    --primary: #3498db;
    --primary-dark: #2176c7;
    --background: #f8fafc;
    --white: #fff;
    --border: #e0e6ed;
    --text: #222;
    --muted: #666;
    --radius: 10px;
}

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--background);
    color: var(--text);
    margin: 0;
    min-height: 100vh;
}

header {
    background: var(--primary);
    color: var(--white);
    padding: 32px 0 20px 0;
    text-align: center;
    border-radius: 0 0 var(--radius) var(--radius);
}

.container {
    max-width: 600px;
    margin: 32px auto;
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: 0 2px 12px rgba(52,152,219,0.06);
    padding: 24px 18px;
}

.search-box {
    display: flex;
    margin-bottom: 24px;
}
.search-box input {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: 6px 0 0 6px;
    font-size: 1rem;
    background: #f3f7fa;
}
.search-box button {
    padding: 10px 20px;
    border: none;
    background: var(--primary);
    color: var(--white);
    border-radius: 0 6px 6px 0;
    font-size: 1rem;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.2s;
}
.search-box button:hover {
    background: var(--primary-dark);
}

.faq-section h2,
.contact-section h2,
.form-section h2 {
    color: var(--primary-dark);
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.faq-list {
    list-style: none;
    padding: 0;
}
.faq-item {
    border-bottom: 1px solid var(--border);
    padding: 12px 0;
}
.faq-question {
    font-weight: 600;
    cursor: pointer;
    color: var(--primary-dark);
    position: relative;
    padding-right: 24px;
}
.faq-question::after {
    content: '+';
    position: absolute;
    right: 0;
    color: var(--primary);
}
.faq-item.open .faq-question::after {
    content: '-';
}
.faq-answer {
    display: none;
    color: var(--muted);
    margin-top: 6px;
    font-size: 0.98rem;
}
.faq-item.open .faq-answer {
    display: block;
}

.contact-section {
    background: #f3f7fa;
    border-radius: 8px;
    padding: 18px 12px;
    margin-bottom: 24px;
}
.contact-options {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.contact-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 10px 12px;
    flex: 1 1 180px;
    min-width: 180px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
}
.contact-card i {
    font-size: 1.4rem;
    color: var(--primary);
}
.contact-card span a {
    color: var(--primary);
    text-decoration: underline;
}

.form-section {
    margin-top: 18px;
}
form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 350px;
}
form input,
form textarea {
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 1rem;
    background: #f3f7fa;
    color: var(--text);
}
form button {
    background: var(--primary);
    color: var(--white);
    border: none;
    padding: 10px;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
form button:hover {
    background: var(--primary-dark);
}

@media (max-width: 700px) {
    .container {
        padding: 10px 2vw;
    }
    .contact-options {
        flex-direction: column;
        gap: 8px;
    }
}
    </style>
    <!-- Simple icons for contact -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <header>
        <h1>Help Center Walbayexpress</h1>
        <p>Temukan jawaban atas pertanyaan Anda atau hubungi tim support kami.</p>
    </header>
    <div class="container">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari pertanyaan atau topik...">
            <button onclick="searchFAQ()">Cari</button>
        </div>
        <section class="faq-section">
            <h2>Pertanyaan yang Sering Diajukan (FAQ)</h2>
            <ul class="faq-list" id="faqList">
                <li class="faq-item">
                    <div class="faq-question">Bagaimana cara membuat akun di Walbayexpress?</div>
                    <div class="faq-answer">Klik tombol "Daftar" di halaman utama, isi data diri Anda, lalu verifikasi email untuk mengaktifkan akun.</div>
                </li>
                <li class="faq-item">
                    <div class="faq-question">Bagaimana cara melacak paket saya?</div>
                    <div class="faq-answer">Masuk ke akun Anda, lalu pilih menu "Lacak Paket". Masukkan nomor resi untuk melihat status pengiriman.</div>
                </li>
                <li class="faq-item">
                    <div class="faq-question">Apa saja metode pembayaran yang tersedia?</div>
                    <div class="faq-answer">Kami menerima pembayaran melalui transfer bank, e-wallet (OVO, GoPay, Dana), dan kartu kredit.</div>
                </li>
                <li class="faq-item">
                    <div class="faq-question">Bagaimana jika paket saya hilang atau rusak?</div>
                    <div class="faq-answer">Segera hubungi customer service kami dengan menyertakan nomor resi dan bukti kerusakan/hilang. Kami akan membantu proses klaim Anda.</div>
                </li>
                <li class="faq-item">
                    <div class="faq-question">Berapa lama waktu pengiriman?</div>
                    <div class="faq-answer">Waktu pengiriman tergantung pada tujuan dan layanan yang dipilih. Estimasi pengiriman dapat dilihat saat pemesanan.</div>
                </li>
                <li class="faq-item">
                    <div class="faq-question">Bagaimana cara menghubungi customer service?</div>
                    <div class="faq-answer">Anda dapat menghubungi kami melalui live chat, email, atau telepon yang tersedia di bawah ini.</div>
                </li>
                <li class="faq-item">
                    <div class="faq-question">Apakah Walbayexpress melayani pengiriman internasional?</div>
                    <div class="faq-answer">Saat ini kami hanya melayani pengiriman domestik di seluruh Indonesia.</div>
                </li>
                <li class="faq-item">
                    <div class="faq-question">Bagaimana cara membatalkan pesanan?</div>
                    <div class="faq-answer">Pesanan dapat dibatalkan sebelum status pengiriman berubah menjadi "Diproses". Silakan hubungi customer service untuk bantuan lebih lanjut.</div>
                </li>
            </ul>
        </section>
        <section class="contact-section">
            <h2>Butuh Bantuan Lebih Lanjut?</h2>
            <div class="contact-options">
                <div class="contact-card">
                    <i class="fas fa-comments"></i>
                    <span>Live Chat: <a href="#" style="color:var(--primary);text-decoration:underline;">Mulai Chat</a></span>
                </div>
                <div class="contact-card">
                    <i class="fas fa-envelope"></i>
                    <span>Email: <a href="mailto:support@walbayexpress.com" style="color:var(--primary);text-decoration:underline;">support@walbayexpress.com</a></span>
                </div>
                <div class="contact-card">
                    <i class="fas fa-phone"></i>
                    <span>Telepon: <a href="tel:02112345678" style="color:var(--primary);text-decoration:underline;">021-12345678</a></span>
                </div>
                <div class="contact-card">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp: <a href="https://wa.me/6281234567890" target="_blank" style="color:var(--primary);text-decoration:underline;">+62 812-3456-7890</a></span>
                </div>
            </div>
        </section>
        <section class="form-section">
            <h2>Kirim Pertanyaan atau Masukan</h2>
            <form id="contactForm" onsubmit="return submitForm(event)">
                <input type="text" name="name" placeholder="Nama Anda" required>
                <input type="email" name="email" placeholder="Email Anda" required>
                <textarea name="message" rows="5" placeholder="Tulis pertanyaan atau masukan Anda..." required></textarea>
                <button type="submit">Kirim</button>
                <div id="formMsg" style="margin-top:8px;color:var(--primary);display:none;"></div>
            </form>
        </section>
    </div>
    <script>
        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(function(q) {
            q.addEventListener('click', function() {
                var item = this.parentElement;
                item.classList.toggle('open');
            });
        });

        // FAQ Search
        function searchFAQ() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var items = document.querySelectorAll('.faq-item');
            items.forEach(function(item) {
                var question = item.querySelector('.faq-question').textContent.toLowerCase();
                var answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                if (question.includes(input) || answer.includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') searchFAQ();
        });

        // Simple form handler (no backend, just demo)
        function submitForm(e) {
            e.preventDefault();
            document.getElementById('formMsg').style.display = 'block';
            document.getElementById('formMsg').textContent = 'Terima kasih! Pesan Anda telah dikirim.';
            document.getElementById('contactForm').reset();
            setTimeout(function() {
                document.getElementById('formMsg').style.display = 'none';
            }, 4000);
            return false;
        }
    </script>
</body>
</html>