// resources/js/sweetalert.js

import Swal from 'sweetalert2';

window.Swal = Swal;

// Alertas de éxito desde sesión (opcional)
window.mostrarAlerta = function (tipo, mensaje) {
    Swal.fire({
        icon: tipo,
        title: tipo === 'success' ? 'Éxito' : 'Aviso',
        text: mensaje,
        confirmButtonColor: '#3085d6',
    });
};

// resources/js/sweetalert.js
window.confirmarEliminacion = function() {
    document.querySelectorAll('form.eliminar-servicio').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault(); // detener envío inmediato
  
        Swal.fire({
          title: '¿Estás seguro?',
          text: "¡No podrás revertir esto!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit(); // enviar formulario si confirma
          }
        });
      });
    });
  };
  
