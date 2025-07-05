// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-active');
        });
    }
    
    // Image preview for product forms
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        const currentImageDiv = document.querySelector('.current-image');
                        if (currentImageDiv) {
                            preview = document.createElement('img');
                            preview.className = 'image-preview';
                            currentImageDiv.insertBefore(preview, currentImageDiv.firstChild);
                        }
                    }
                    if (preview) {
                        preview.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Confirm before delete
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this product?')) {
                e.preventDefault();
            }
        });
    });
});