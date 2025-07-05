<?php
// Contoh data properti lebih kompleks
$properties = [
    [
        'nama' => 'Rumah Minimalis',
        'alamat' => 'Jl. Melati No. 10, Jakarta',
        'harga' => 850000000,
        'foto' => 'rumah1.jpg',
        'tipe' => 'Rumah',
        'luas' => '120 m²',
        'kamar_tidur' => 3,
        'kamar_mandi' => 2,
        'fasilitas' => ['Garasi', 'Taman', 'Keamanan 24 Jam'],
        'agen' => [
            'nama' => 'Dewi Lestari',
            'foto' => 'agen1.jpg',
            'kontak' => '0812-3456-7890'
        ]
    ],
    [
        'nama' => 'Apartemen Modern',
        'alamat' => 'Jl. Sudirman No. 20, Bandung',
        'harga' => 1200000000,
        'foto' => null,
        'tipe' => 'Apartemen',
        'luas' => '85 m²',
        'kamar_tidur' => 2,
        'kamar_mandi' => 1,
        'fasilitas' => ['Kolam Renang', 'Gym', 'Parkir Basement'],
        'agen' => [
            'nama' => 'Budi Santoso',
            'foto' => null,
            'kontak' => '0821-9876-5432'
        ]
    ]
];

// Fungsi format rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Properti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(120deg, #e0eafc 60%, #cfdef3 100%);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 48px auto;
            padding: 32px 24px;
            background: rgba(255,255,255,0.95);
            border-radius: 22px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.13);
        }
        h2 {
            text-align: center;
            color: #2d3a4b;
            margin-bottom: 38px;
            letter-spacing: 1.5px;
            font-weight: 700;
            font-size: 2.2em;
        }
        .property-list {
            display: flex;
            flex-wrap: wrap;
            gap: 36px;
            justify-content: center;
        }
        .property-card {
            background: linear-gradient(120deg, #f9fbfd 80%, #eaf6ff 100%);
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(44,62,80,0.09);
            border: 1px solid #e0e0e0;
            padding: 26px 20px 20px 20px;
            width: 340px;
            transition: box-shadow 0.2s, transform 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .property-card:hover {
            box-shadow: 0 12px 36px rgba(44,62,80,0.16);
            transform: translateY(-6px) scale(1.025);
        }
        .property-img {
            width: 100%;
            max-width: 260px;
            height: 160px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 18px;
            background: #f0f0f0;
            display: block;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
        }
        .property-title {
            margin: 0 0 8px 0;
            font-size: 1.25em;
            color: #2d3a4b;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .property-type {
            font-size: 0.98em;
            color: #6c7a89;
            margin-bottom: 8px;
            font-weight: 500;
            text-align: center;
        }
        .property-address {
            color: #6c7a89;
            font-size: 0.98em;
            margin-bottom: 14px;
            text-align: center;
        }
        .property-info {
            display: flex;
            gap: 18px;
            justify-content: center;
            margin-bottom: 10px;
        }
        .property-info span {
            display: flex;
            align-items: center;
            font-size: 0.97em;
            color: #34495e;
            gap: 5px;
        }
        .property-price {
            color: #1abc9c;
            font-weight: bold;
            font-size: 1.18em;
            margin-bottom: 14px;
            text-align: center;
            letter-spacing: 1px;
        }
        .property-facilities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-bottom: 16px;
        }
        .facility-badge {
            background: #eafaf1;
            color: #16a085;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 0.93em;
            font-weight: 500;
            border: 1px solid #b2dfdb;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .agent-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            background: #f7fafd;
            border-radius: 8px;
            padding: 7px 12px;
            width: 100%;
            box-sizing: border-box;
        }
        .agent-photo {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
            background: #f0f0f0;
        }
        .agent-info {
            display: flex;
            flex-direction: column;
            font-size: 0.97em;
        }
        .agent-name {
            font-weight: 600;
            color: #2d3a4b;
        }
        .agent-contact {
            color: #16a085;
            font-size: 0.95em;
        }
        .property-noimg {
            font-style: italic;
            color: #b0b0b0;
            margin-bottom: 16px;
            display: block;
            width: 100%;
            text-align: center;
        }
        @media (max-width: 800px) {
            .container {
                padding: 10px;
            }
            .property-list {
                gap: 18px;
            }
            .property-card {
                width: 98vw;
                max-width: 370px;
            }
        }
        @media (max-width: 500px) {
            .property-card {
                padding: 12px 6px 12px 6px;
            }
            .property-img {
                max-width: 98vw;
                height: 120px;
            }
        }
    </style>
</head>
<body>
    <div style="display:flex;align-items:center;justify-content:center;margin-top:24px;margin-bottom:-18px;font-size:1.05em;color:#2d3a4b;font-weight:500;gap:8px;">
        <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/logo-walbayexpress.png" alt="Walbayexpress Logo" style="width:28px;height:28px;vertical-align:middle;border-radius:6px;box-shadow:0 1px 4px rgba(44,62,80,0.10);margin-right:6px;">
        Dikelola oleh Walbayexpress
    </div>
    <div class="container">
        <h2>Daftar Properti Pilihan</h2>
        <div class="property-list">
            <?php foreach ($properties as $prop): ?>
                <div class="property-card">
                    <?php if ($prop['foto']): ?>
                        <img src="<?= htmlspecialchars($prop['foto']) ?>" alt="Foto <?= htmlspecialchars($prop['nama']) ?>" class="property-img" onerror="this.onerror=null;this.src="/PWDTUBES_WalBayExpress/assets/img/tentang/Plan 871008NST_ Backyard Cottage or Home Office Escape - 298 Square Feet.jpeg">
                    <?php else: ?>
                        <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/_3.jpeg" alt="Foto tidak tersedia" class="property-img">
                    <?php endif; ?>
                    <h3 class="property-title"><?= htmlspecialchars($prop['nama']) ?></h3>
                    <div class="property-type"><i class="fa-solid fa-building"></i> <?= htmlspecialchars($prop['tipe']) ?></div>
                    <div class="property-address"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($prop['alamat']) ?></div>
                    <div class="property-info">
                        <span title="Luas"><i class="fa-solid fa-ruler-combined"></i> <?= htmlspecialchars($prop['luas']) ?></span>
                        <span title="Kamar Tidur"><i class="fa-solid fa-bed"></i> <?= htmlspecialchars($prop['kamar_tidur']) ?></span>
                        <span title="Kamar Mandi"><i class="fa-solid fa-bath"></i> <?= htmlspecialchars($prop['kamar_mandi']) ?></span>
                    </div>
                    <div class="property-price"><?= formatRupiah($prop['harga']) ?></div>
                    <div class="property-facilities">
                        <?php foreach ($prop['fasilitas'] as $fasilitas): ?>
                            <span class="facility-badge"><i class="fa-solid fa-check"></i> <?= htmlspecialchars($fasilitas) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="agent-section">
                        <?php if (!empty($prop['agen']['foto'])): ?>
                            <img src="<?= htmlspecialchars($prop['agen']['foto']) ?>" alt="Agen <?= htmlspecialchars($prop['agen']['nama']) ?>" class="agent-photo" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=<?= urlencode($prop['agen']['nama']) ?>&background=16a085&color=fff&size=38';">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($prop['agen']['nama']) ?>&background=16a085&color=fff&size=38" alt="Agen" class="agent-photo">
                        <?php endif; ?>
                        <div class="agent-info">
                            <span class="agent-name"><?= htmlspecialchars($prop['agen']['nama']) ?></span>
                            <span class="agent-contact"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($prop['agen']['kontak']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
