/**
 * WAKANDE - Admin Panel JavaScript
 * Handles admin-specific functionality
 */

import './bootstrap';
import './theme';

// ===== DOM CONTENT LOADED =====
document.addEventListener('DOMContentLoaded', function() {
    initializeAdminCharts();
    initializeModeration();
    initializeUserManagement();
    initializeTransactionMonitoring();
    initializeDashboardWidgets();
});

// ===== DASHBOARD CHARTS =====
function initializeAdminCharts() {
    // Transaction Chart
    const transactionCtx = document.getElementById('transactionChart');
    if (transactionCtx) {
        new Chart(transactionCtx, {
            type: 'line',
            data: {
                labels: JSON.parse(transactionCtx.dataset.labels || '[]'),
                datasets: [
                    {
                        label: 'Transaksi',
                        data: JSON.parse(transactionCtx.dataset.transactions || '[]'),
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Pendapatan',
                        data: JSON.parse(transactionCtx.dataset.revenue || '[]'),
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            boxHeight: 8
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.02)'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            display: false
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: JSON.parse(categoryCtx.dataset.labels || '[]'),
                datasets: [{
                    data: JSON.parse(categoryCtx.dataset.data || '[]'),
                    backgroundColor: [
                        '#667eea',
                        '#198754',
                        '#ffc107',
                        '#0dcaf0',
                        '#dc3545'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
}

// ===== MODERATION =====
function initializeModeration() {
    // Auto-expand textareas
    const textareas = document.querySelectorAll('textarea.auto-expand');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    // Bulk moderation actions
    const bulkActionBtn = document.getElementById('bulk-action');
    if (bulkActionBtn) {
        bulkActionBtn.addEventListener('click', handleBulkModeration);
    }

    // Filter badges
    initializeFilterBadges();
}

function handleBulkModeration() {
    const selectedItems = Array.from(document.querySelectorAll('.moderation-checkbox:checked'))
        .map(cb => cb.value);

    if (selectedItems.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Ada Item Dipilih',
            text: 'Pilih minimal satu item untuk dimoderasi'
        });
        return;
    }

    const action = document.getElementById('bulk-action-select')?.value;

    if (!action) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Aksi',
            text: 'Pilih aksi yang ingin dilakukan'
        });
        return;
    }

    Swal.fire({
        title: `Moderasi ${selectedItems.length} Item?`,
        text: `Kamu yakin ingin ${action === 'approve' ? 'menyetujui' : 'menolak'} item yang dipilih?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: action === 'approve' ? '#198754' : '#dc3545',
        confirmButtonText: action === 'approve' ? 'Ya, Setujui' : 'Ya, Tolak'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit bulk moderation form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/moderation/bulk-${action}`;

            const csrf = document.createElement('input');
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrf);

            selectedItems.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'item_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
}

// ===== USER MANAGEMENT =====
function initializeUserManagement() {
    // User search with debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.closest('form')?.submit();
            }, 500);
        });
    }

    // Export users
    const exportBtn = document.getElementById('export-users');
    if (exportBtn) {
        exportBtn.addEventListener('click', exportUsers);
    }
}

function exportUsers() {
    const filters = {
        role: document.querySelector('select[name="role"]')?.value,
        status: document.querySelector('select[name="status"]')?.value,
        search: document.querySelector('input[name="search"]')?.value
    };

    const params = new URLSearchParams(filters);
    window.location.href = `/admin/users/export?${params.toString()}`;
}

// ===== TRANSACTION MONITORING =====
function initializeTransactionMonitoring() {
    // Date range picker
    const dateRangeInput = document.getElementById('date-range');
    if (dateRangeInput) {
        flatpickr(dateRangeInput, {
            mode: 'range',
            dateFormat: 'd/m/Y',
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const [start, end] = selectedDates;
                    document.getElementById('date_from').value = moment(start).format('YYYY-MM-DD');
                    document.getElementById('date_to').value = moment(end).format('YYYY-MM-DD');
                    document.getElementById('filterForm').submit();
                }
            }
        });
    }

    // Payment status filter
    const statusSelect = document.querySelector('select[name="payment_status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
}

// ===== DASHBOARD WIDGETS =====
function initializeDashboardWidgets() {
    // Refresh widgets
    const refreshBtn = document.getElementById('refresh-dashboard');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.classList.add('spin');
            setTimeout(() => this.classList.remove('spin'), 1000);

            // Reload chart data
            reloadChartData();
        });
    }

    // Quick action buttons
    initializeQuickActions();
}

async function reloadChartData() {
    try {
        const response = await fetch('/admin/dashboard/chart-data');
        const data = await response.json();

        // Update charts with new data
        const transactionChart = Chart.getChart('transactionChart');
        if (transactionChart) {
            transactionChart.data.labels = data.labels;
            transactionChart.data.datasets[0].data = data.transactions;
            transactionChart.data.datasets[1].data = data.revenue;
            transactionChart.update();
        }

        const categoryChart = Chart.getChart('categoryChart');
        if (categoryChart) {
            categoryChart.data.datasets[0].data = data.categories;
            categoryChart.update();
        }
    } catch (error) {
        console.error('Failed to reload chart data:', error);
    }
}

function initializeQuickActions() {
    // Suspend user
    document.querySelectorAll('.suspend-user').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            Swal.fire({
                title: 'Nonaktifkan User?',
                text: `Akun ${userName} akan dinonaktifkan`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Nonaktifkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    toggleUserStatus(userId, false);
                }
            });
        });
    });

    // Activate user
    document.querySelectorAll('.activate-user').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            Swal.fire({
                title: 'Aktifkan User?',
                text: `Akun ${userName} akan diaktifkan kembali`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Ya, Aktifkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    toggleUserStatus(userId, true);
                }
            });
        });
    });

    // Delete user
    document.querySelectorAll('.delete-user').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            Swal.fire({
                title: 'Hapus User?',
                html: `<p>Kamu yakin ingin menghapus akun <strong>${userName}</strong>?</p>
                       <p class="small text-danger">Semua data terkait akan ikut terhapus!</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteUser(userId);
                }
            });
        });
    });
}

function toggleUserStatus(userId, activate) {
    fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `Status user berhasil diubah`,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

function deleteUser(userId) {
    fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Dihapus!',
                text: 'User berhasil dihapus',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

// ===== FILTER BADGES =====
function initializeFilterBadges() {
    document.querySelectorAll('.filter-badge .btn-close').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const filterName = this.dataset.filter;
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            window.location.href = url.toString();
        });
    });
}

// ===== SIDEBAR TOGGLE =====
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('adminSidebar');

if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
}

// ===== ADMIN NOTIFICATIONS =====
function checkPendingItems() {
    const pendingCount = document.getElementById('pending-count');
    if (pendingCount) {
        fetch('/admin/moderation/pending/count')
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    pendingCount.textContent = data.count;
                    pendingCount.classList.remove('d-none');
                } else {
                    pendingCount.classList.add('d-none');
                }
            });
    }
}

// Check pending items every 30 seconds
setInterval(checkPendingItems, 30000);

// ===== EXPORT =====
export {
    initializeAdminCharts,
    initializeModeration,
    initializeUserManagement,
    initializeTransactionMonitoring
};
