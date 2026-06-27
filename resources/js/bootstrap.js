import 'bootstrap';

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

document.addEventListener('show.bs.modal', function (e) {
    if (e.target && e.target.parentElement !== document.body) {
        document.body.appendChild(e.target);
    }
});
