import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
 
import './sweetalert';

document.addEventListener('DOMContentLoaded', () => {
    confirmarEliminacion();
});
