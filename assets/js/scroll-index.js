<script>
    const scrollContainer = document.querySelector('.category-tiles');
    const btnLeft = document.getElementById('scroll-left');
    const btnRight = document.getElementById('scroll-right');

    if (btnLeft && btnRight && scrollContainer) {
        btnLeft.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
        });

        btnRight.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }
</script>