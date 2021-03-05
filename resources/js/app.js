require('./bootstrap');

// Import modules...
import {createApp, h} from 'vue';
import {App as InertiaApp, plugin as InertiaPlugin} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({methods: {route}})
    .use(InertiaPlugin)
    .mount(el);

InertiaProgress.init({color: '#4B5563'});

//Import Sweetalert2
import Swal from 'sweetalert2';

window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
window.Toast = Toast;

// import LaravelVuePagination from 'laravel-vue-tailwind-pagination';
//
// Vue.use(LaravelVuePagination);

// Vue.component('tailable-pagination',require('tailable-pagination'));


