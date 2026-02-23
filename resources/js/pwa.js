/**
 * WAKANDE - Progressive Web App (PWA) Configuration
 * Handles service worker registration and offline capabilities
 */

// ===== SERVICE WORKER REGISTRATION =====
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        registerServiceWorker();
    });
}

async function registerServiceWorker() {
    try {
        const registration = await navigator.serviceWorker.register('/sw.js', {
            scope: '/'
        });

        console.log('✅ Service Worker registered with scope:', registration.scope);

        // Check for updates
        registration.addEventListener('updatefound', () => {
            const newWorker = registration.installing;
            console.log('🔄 New service worker found');

            newWorker.addEventListener('statechange', () => {
                if (newWorker.state === 'installed') {
                    if (navigator.serviceWorker.controller) {
                        // New update available
                        showUpdateNotification(registration);
                    }
                }
            });
        });

        // Initial setup
        await setupPWA();

    } catch (error) {
        console.error('❌ Service Worker registration failed:', error);
    }
}

// ===== PWA SETUP =====
async function setupPWA() {
    // Check if already installed
    checkInstalledStatus();

    // Listen for beforeinstallprompt event
    setupInstallPrompt();

    // Check network status
    setupNetworkDetection();

    // Set up sync
    setupBackgroundSync();
}

// ===== INSTALL PROMPT =====
let deferredPrompt;
const installButton = document.getElementById('install-pwa');

function setupInstallPrompt() {
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();

        // Stash the event so it can be triggered later
        deferredPrompt = e;

        // Show install button if available
        if (installButton) {
            installButton.style.display = 'block';

            installButton.addEventListener('click', async () => {
                if (!deferredPrompt) return;

                // Show the install prompt
                deferredPrompt.prompt();

                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;

                if (outcome === 'accepted') {
                    console.log('✅ User accepted the install prompt');
                    trackInstallation();
                } else {
                    console.log('❌ User dismissed the install prompt');
                }

                // Clear the saved prompt since it can't be used again
                deferredPrompt = null;
                installButton.style.display = 'none';
            });
        }
    });

    window.addEventListener('appinstalled', (evt) => {
        console.log('✅ WAKANDE was installed successfully');

        // Hide install button
        if (installButton) {
            installButton.style.display = 'none';
        }

        // Track installation
        trackInstallation();

        // Show welcome notification
        showInstallSuccess();
    });
}

// ===== INSTALLED STATUS =====
function checkInstalledStatus() {
    // Check if running as standalone PWA
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches ||
                        window.navigator.standalone === true;

    if (isStandalone) {
        document.body.classList.add('pwa-mode');
        console.log('📱 Running as standalone PWA');

        // Hide install button if in standalone mode
        if (installButton) {
            installButton.style.display = 'none';
        }
    }
}

// ===== NETWORK DETECTION =====
function setupNetworkDetection() {
    function updateOnlineStatus() {
        const offlineIndicator = document.getElementById('offline-indicator');

        if (navigator.onLine) {
            document.body.classList.remove('offline-mode');
            if (offlineIndicator) {
                offlineIndicator.style.display = 'none';
            }
            console.log('🌐 Back online');
        } else {
            document.body.classList.add('offline-mode');
            if (offlineIndicator) {
                offlineIndicator.style.display = 'block';
            }
            console.log('📴 Offline mode');

            // Show offline notification
            showOfflineNotification();
        }
    }

    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    // Initial check
    updateOnlineStatus();
}

// ===== BACKGROUND SYNC =====
async function setupBackgroundSync() {
    if ('serviceWorker' in navigator && 'SyncManager' in window) {
        try {
            const registration = await navigator.serviceWorker.ready;

            // Register for sync when offline actions are queued
            window.addEventListener('offline-action-queued', async () => {
                try {
                    await registration.sync.register('sync-transactions');
                    console.log('📤 Background sync registered');
                } catch (error) {
                    console.error('❌ Background sync registration failed:', error);
                }
            });
        } catch (error) {
            console.error('❌ Failed to setup background sync:', error);
        }
    }
}

// ===== NOTIFICATIONS =====
async function showUpdateNotification(registration) {
    const updateNotification = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: true,
        confirmButtonText: 'Perbarui',
        showCancelButton: true,
        cancelButtonText: 'Nanti',
        timer: null,
        timerProgressBar: false,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    const result = await updateNotification.fire({
        icon: 'info',
        title: 'Update Tersedia! 🚀',
        text: 'Versi baru WAKANDE tersedia. Perbarui sekarang?'
    });

    if (result.isConfirmed) {
        if (registration.waiting) {
            // Send message to waiting service worker
            registration.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
        window.location.reload();
    }
}

function showOfflineNotification() {
    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        icon: 'warning',
        title: 'Kamu sedang offline',
        text: 'Beberapa fitur mungkin tidak tersedia',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    toast.fire();
}

function showInstallSuccess() {
    Swal.fire({
        icon: 'success',
        title: 'Terima kasih! 🎉',
        html: '<p class="mb-0">WAKANDE berhasil diinstall ke perangkatmu.</p>' +
              '<p class="small text-secondary mt-2">Sekarang bisa diakses offline dan lebih cepat.</p>',
        confirmButtonText: 'Mulai',
        confirmButtonColor: '#667eea'
    });
}

// ===== TRACKING =====
function trackInstallation() {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'pwa_install', {
            'event_category': 'engagement',
            'event_label': 'PWA Installation'
        });
    }

    // Send to analytics API
    if (navigator.onLine) {
        fetch('/api/track/pwa-install', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({
                platform: navigator.platform,
                userAgent: navigator.userAgent,
                timestamp: new Date().toISOString()
            })
        }).catch(error => console.error('Failed to track installation:', error));
    }
}

// ===== CACHE MANAGEMENT =====
async function clearCache() {
    if ('caches' in window) {
        const cacheNames = await caches.keys();

        await Promise.all(
            cacheNames
                .filter(name => name.startsWith('wakande-'))
                .map(name => caches.delete(name))
        );

        console.log('🧹 Cache cleared');
    }
}

// ===== OFFLINE QUEUE =====
class OfflineQueue {
    constructor() {
        this.queue = [];
        this.loadFromStorage();
    }

    add(action) {
        this.queue.push({
            ...action,
            id: Date.now(),
            timestamp: new Date().toISOString()
        });

        this.saveToStorage();

        // Try to sync if online
        if (navigator.onLine) {
            this.sync();
        } else {
            window.dispatchEvent(new Event('offline-action-queued'));
        }
    }

    async sync() {
        while (this.queue.length > 0) {
            const action = this.queue[0];

            try {
                const response = await fetch(action.url, {
                    method: action.method,
                    headers: action.headers,
                    body: JSON.stringify(action.data)
                });

                if (response.ok) {
                    this.queue.shift();
                    this.saveToStorage();
                } else {
                    break;
                }
            } catch (error) {
                console.error('Failed to sync action:', error);
                break;
            }
        }
    }

    saveToStorage() {
        localStorage.setItem('offlineQueue', JSON.stringify(this.queue));
    }

    loadFromStorage() {
        const stored = localStorage.getItem('offlineQueue');
        if (stored) {
            try {
                this.queue = JSON.parse(stored);
            } catch (error) {
                console.error('Failed to load offline queue:', error);
            }
        }
    }
}

// Initialize offline queue
window.offlineQueue = new OfflineQueue();

// ===== EXPOSE PWA FUNCTIONS GLOBALLY =====
window.pwa = {
    install: () => deferredPrompt?.prompt(),
    clearCache,
    isStandalone: () => window.matchMedia('(display-mode: standalone)').matches,
    getVersion: () => '1.0.0',
    checkForUpdates: registerServiceWorker
};

// ===== PWA EVENT LISTENERS =====
document.addEventListener('DOMContentLoaded', () => {
    // Add offline indicator to DOM if not exists
    if (!document.getElementById('offline-indicator')) {
        const indicator = document.createElement('div');
        indicator.id = 'offline-indicator';
        indicator.className = 'position-fixed bottom-0 start-0 m-3 bg-warning text-white rounded-pill px-4 py-2 shadow-lg';
        indicator.style.display = 'none';
        indicator.style.zIndex = '9999';
        indicator.innerHTML = '<i class="bi bi-wifi-off me-2"></i>Offline Mode';
        document.body.appendChild(indicator);
    }

    // Add install button to navbar if not exists
    if (!document.getElementById('install-pwa')) {
        const navbar = document.querySelector('.navbar-nav:last-child');
        if (navbar && !document.querySelector('.pwa-mode')) {
            const installBtn = document.createElement('button');
            installBtn.id = 'install-pwa';
            installBtn.className = 'btn btn-outline-primary btn-rounded ms-2';
            installBtn.style.display = 'none';
            installBtn.innerHTML = '<i class="bi bi-download me-1"></i>Install App';
            navbar.parentElement?.appendChild(installBtn);
        }
    }
});
