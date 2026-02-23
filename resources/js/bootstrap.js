/**
 * WAKANDE - Bootstrap Configuration
 * Loads and configures Bootstrap and its dependencies
 */

import _ from 'lodash';
window._ = _;

import * as Popper from '@popperjs/core';
window.Popper = Popper;

import 'bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
//
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

/**
 * Axios is a promise-based HTTP client
 */
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;

/**
 * SweetAlert2 for beautiful alerts
 */
import Swal from 'sweetalert2';
window.Swal = Swal;

// SweetAlert2 default configuration
window.Swal.fire = Swal.fire;
window.Swal.mixin({
    confirmButtonColor: '#667eea',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    customClass: {
        popup: 'rounded-4',
        confirmButton: 'btn btn-primary rounded-pill px-4 mx-1',
        cancelButton: 'btn btn-outline-secondary rounded-pill px-4 mx-1'
    },
    buttonsStyling: false
});

/**
 * Alpine.js for lightweight interactivity
 */
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/**
 * Chart.js for data visualization
 */
import Chart from 'chart.js/auto';
window.Chart = Chart;

// Chart.js default configuration
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.padding = 12;
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.boxWidth = 8;
Chart.defaults.plugins.legend.labels.boxHeight = 8;
Chart.defaults.responsive = true;
Chart.defaults.maintainAspectRatio = false;

/**
 * Flatpickr for date picker
 */
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'flatpickr/dist/l10n/id';
window.flatpickr = flatpickr;

// Flatpickr default configuration
window.flatpickr.defaultConfig = {
    locale: 'id',
    dateFormat: 'd/m/Y',
    altFormat: 'd F Y',
    altInput: true,
    altInputClass: 'form-control rounded-3',
    allowInput: true
};

/**
 * Select2 for enhanced selects
 */
import select2 from 'select2';
select2();
window.select2 = select2;

/**
 * Moment.js for date manipulation
 */
import moment from 'moment';
import 'moment/locale/id';
window.moment = moment;
moment.locale('id');

/**
 * Imask for input masking
 */
import IMask from 'imask';
window.IMask = IMask;

/**
 * Cleave.js for formatting
 */
import Cleave from 'cleave.js';
window.Cleave = Cleave;

/**
 * Lightbox for image gallery
 */
import lightbox from 'lightbox2';
window.lightbox = lightbox;

/**
 * AOS - Animate On Scroll
 */
import AOS from 'aos';
import 'aos/dist/aos.css';
window.AOS = AOS;

AOS.init({
    duration: 800,
    once: true,
    offset: 100,
    delay: 100,
    easing: 'ease-in-out',
    mirror: false
});

/**
 * Lozad - Lazy load images
 */
import lozad from 'lozad';
window.lozad = lozad;

const observer = lozad('.lozad', {
    rootMargin: '100px 0px',
    threshold: 0.1,
    loaded: function(el) {
        el.classList.add('loaded');
    }
});
observer.observe();

// Bootstrap 5 validation styles
import '../sass/app.scss';
