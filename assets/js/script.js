// Add interactivity to the login form
document.addEventListener('DOMContentLoaded', () => {
    // Form Validation
    const forms = document.querySelectorAll('form');
    forms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
            let isValid = true;

            inputs.forEach((input) => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    input.nextElementSibling?.classList.add('text-danger');
                    input.nextElementSibling.textContent = `${input.name} is required.`;
                } else {
                    input.classList.remove('is-invalid');
                    input.nextElementSibling?.classList.remove('text-danger');
                    input.nextElementSibling.textContent = '';
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Dynamic Sidebar Toggle
    const sidebarToggle = document.querySelector('#sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
        });
    }

    // Product Card Hover Effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card) => {
        card.addEventListener('mouseover', () => {
            card.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.2)';
            card.style.transform = 'scale(1.05)';
            card.style.transition = 'all 0.3s ease';
        });
        card.addEventListener('mouseout', () => {
            card.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.1)';
            card.style.transform = 'scale(1)';
        });
    });

    // Smooth Scroll for Anchor Links
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const targetId = link.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop,
                    behavior: 'smooth',
                });
            }
        });
    });

    // Toast Notifications
    const showToast = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    };

    // Example Usage of Toast Notifications
    document.querySelectorAll('.btn-success').forEach((btn) => {
        btn.addEventListener('click', () => {
            showToast('Action completed successfully!', 'success');
        });
    });

    // Responsive Navbar Dropdown
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            const dropdownMenu = toggle.nextElementSibling;
            dropdownMenu.classList.toggle('show');
        });
    });
});