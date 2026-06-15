document.addEventListener('DOMContentLoaded', function() {
    // ─── Sidebar Toggle ───
    const sidebar = document.getElementById('admin-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            if (sidebarOverlay) {
                sidebarOverlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
            }
        });
    }

    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.style.display = 'none';
        });
    }

    // ─── Toast Notifications ───
    window.showToast = function(message, type = 'success') {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icon = type === 'success' 
            ? '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
            : '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';

        toast.innerHTML = `${icon} <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    };

    // Show server-side flashes if embedded in HTML
    const flashSuccess = document.getElementById('flash-success');
    if (flashSuccess && flashSuccess.dataset.message) showToast(flashSuccess.dataset.message, 'success');
    
    const flashError = document.getElementById('flash-error');
    if (flashError && flashError.dataset.message) showToast(flashError.dataset.message, 'danger');

    // ─── Confirm Actions ───
    const confirmForms = document.querySelectorAll('.confirm-action');
    confirmForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const msg = this.dataset.confirm || 'دڵنیایت لەم کارە؟';
            if (confirm(msg)) {
                this.submit();
            }
        });
    });

    // ─── Image Preview ───
    const imageInputs = document.querySelectorAll('.image-upload-input');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.dataset.preview;
            if (!previewId) return;
            const previewImg = document.getElementById(previewId);
            if (!previewImg) return;

            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    });
});
