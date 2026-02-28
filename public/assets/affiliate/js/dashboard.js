document.addEventListener('DOMContentLoaded', function() {
    // Add active class toggling for menu items
    const menuItems = document.querySelectorAll('.MenuItem');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Remove active from all
            menuItems.forEach(mi => mi.classList.remove('active'));
            // Add to clicked
            this.classList.add('active');
        });
    });

    // Handle logout click
    const logoutBtn = document.querySelector('.LogoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Assuming there's a logout form somewhere
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.submit();
            } else {
                // Determine base URL, or just use /logout
                window.location.href = '/logout';
            }
        });
    }
});
