/**
 * WAKANDE - Main Application JavaScript
 * Handles core functionality, global components, and utilities
 */

import './bootstrap';
import 'bootstrap';
import './theme';
import './pwa';

// ===== DOM CONTENT LOADED =====
document.addEventListener('DOMContentLoaded', function() {
    initializeTooltips();
    initializePopovers();
    initializeFormValidation();
    initializeFileUploads();
    initializeCountdowns();
    initializeAutoDismissAlerts();
});

// ===== TOOLTIPS =====
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            animation: true,
            delay: { show: 500, hide: 100 }
        });
    });
}

// ===== POPOVERS =====
function initializePopovers() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            animation: true,
            trigger: 'hover'
        });
    });
}

// ===== FORM VALIDATION =====
function initializeFormValidation() {
    // Custom validation for Bootstrap 5
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Email belajar.id auto-complete
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            let email = this.value;
            if (email && !email.includes('@') && !email.includes('@belajar.id')) {
                this.value = email + '@belajar.id';
            }
        });
    });

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            }
            this.value = value;
        });
    });
}

// ===== FILE UPLOADS =====
function initializeFileUploads() {
    // Drag & drop file upload
    const uploadAreas = document.querySelectorAll('.upload-area');

    uploadAreas.forEach(area => {
        const input = area.querySelector('input[type="file"]');
        if (!input) return;

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area
        ['dragenter', 'dragover'].forEach(eventName => {
            area.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        area.addEventListener('drop', handleDrop, false);

        // Handle click
        area.addEventListener('click', () => input.click());

        // Handle file selection
        input.addEventListener('change', function() {
            handleFiles(this.files, area);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            area.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
        }

        function unhighlight() {
            area.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            input.files = files;
            handleFiles(files, area);
        }

        function handleFiles(files, area) {
            const previewContainer = area.closest('.form-step-card')?.querySelector('.preview-container');
            if (previewContainer) {
                previewContainer.innerHTML = '';

                Array.from(files).forEach((file, index) => {
                    if (index < 5) { // Max 5 files
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4 col-lg-3';
                            col.innerHTML = `
                                <div class="position-relative">
                                    <img src="${e.target.result}"
                                         class="w-100 rounded-3"
                                         style="object-fit: cover; aspect-ratio: 1;">
                                    <button type="button"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle p-1"
                                            onclick="removeFile(this, ${index})"
                                            style="width: 30px; height: 30px;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            `;
                            previewContainer.appendChild(col);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
    });
}

// ===== COUNTDOWN TIMERS =====
function initializeCountdowns() {
    const countdownElements = document.querySelectorAll('[data-countdown]');

    countdownElements.forEach(element => {
        const endTime = new Date(element.dataset.countdown).getTime();

        const interval = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(interval);
                element.innerHTML = 'Expired';
                element.classList.add('text-danger');
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            element.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        }, 1000);
    });
}

// ===== AUTO DISMISS ALERTS =====
function initializeAutoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert.alert-auto-dismiss');

    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

// ===== GLOBAL UTILITIES =====

// Format currency
window.formatCurrency = function(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount).replace('Rp', 'Rp ');
};

// Format date
window.formatDate = function(date, format = 'full') {
    const d = new Date(date);
    if (format === 'full') {
        return d.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } else if (format === 'short') {
        return d.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
    return d.toLocaleDateString('id-ID');
};

// Truncate text
window.truncateText = function(text, length = 100) {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
};

// Debounce function
window.debounce = function(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

// Throttle function
window.throttle = function(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = setTimeout(() => inThrottle = false, limit);
        }
    };
};

// Copy to clipboard
window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: 'Berhasil disalin ke clipboard',
            showConfirmButton: false,
            timer: 1500
        });
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
};

// ===== REMOVE FILE FUNCTION (GLOBAL) =====
window.removeFile = function(button, index) {
    const previewContainer = button.closest('.col-6, .col-4, .col-3');
    if (previewContainer) {
        previewContainer.remove();
    }

    const fileInput = button.closest('.form-step-card')?.querySelector('input[type="file"]');
    if (fileInput) {
        const dt = new DataTransfer();
        const files = fileInput.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }

        fileInput.files = dt.files;
    }
};

// ===== AJAX REQUEST HELPER =====
window.ajax = function(options) {
    const defaults = {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    };

    const config = { ...defaults, ...options };

    return fetch(config.url, config)
        .then(response => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        });
};

// ===== LAZY LOAD IMAGES =====
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img.lazy').forEach(img => imageObserver.observe(img));
}

// ===== BACK TO TOP BUTTON =====
const backToTopBtn = document.getElementById('back-to-top');
if (backToTopBtn) {
    window.addEventListener('scroll', throttle(function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    }, 100));

    backToTopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// ===== RESPONSIVE TABLES =====
const tables = document.querySelectorAll('.table-responsive');
tables.forEach(table => {
    const wrapper = document.createElement('div');
    wrapper.className = 'table-responsive-wrapper';
    table.parentNode.insertBefore(wrapper, table);
    wrapper.appendChild(table);
});
