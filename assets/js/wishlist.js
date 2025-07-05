document.querySelectorAll('.watch-btn').forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault();
        const productId = this.dataset.productId;

        fetch('/PWDTUBES_WalBayExpress/menus/wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect ke halaman wishlist setelah sukses
                window.location.href = '/PWDTUBES_WalBayExpress/menus/wishlist.php';
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            alert('Failed to update wishlist. Please try again later.');
            console.error(error);
        });
    });
});