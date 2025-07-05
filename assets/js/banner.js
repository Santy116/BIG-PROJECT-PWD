
    (function() {
        const container = document.querySelector('.banner-container');
        const slides = document.querySelectorAll('.banner-slide');
        const dots = document.querySelectorAll('.banner-dots .dot');
        const prevButton = document.querySelector('.carousel__control--prev');
        const nextButton = document.querySelector('.carousel__control--next');
        let current = 0;
        let timer;

        // Fungsi untuk menampilkan slide berdasarkan indeks
        function showSlide(idx) {
            container.style.transform = `translateX(-${idx * 100}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            if (dots[idx]) dots[idx].classList.add('active');
            current = idx;
        }

        // Fungsi untuk pindah ke slide berikutnya
        function nextSlide() {
            showSlide((current + 1) % slides.length);
        }

        // Fungsi untuk pindah ke slide sebelumnya
        function prevSlide() {
            showSlide((current - 1 + slides.length) % slides.length);
        }

        // Fungsi untuk memulai auto-slide
        function startAuto() {
            timer = setInterval(nextSlide, 3500);
        }

        // Fungsi untuk menghentikan auto-slide
        function stopAuto() {
            clearInterval(timer);
        }

        // Event listener untuk tombol Next
        nextButton.addEventListener('click', () => {
            stopAuto();
            nextSlide();
            startAuto();
        });

        // Event listener untuk tombol Previous
        prevButton.addEventListener('click', () => {
            stopAuto();
            prevSlide();
            startAuto();
        });

        // Event listener untuk titik navigasi
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                stopAuto();
                showSlide(Number(this.dataset.slide));
                startAuto();
            });
        });

        // Hentikan auto-slide saat mouse berada di banner
        container.addEventListener('mouseenter', stopAuto);
        container.addEventListener('mouseleave', startAuto);

        // Inisialisasi slide pertama dan mulai auto-slide
        showSlide(0);
        startAuto();
    })();
