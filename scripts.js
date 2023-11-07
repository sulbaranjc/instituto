    function validarNombre() {
        var nombre = document.getElementById('nombre').value;
        var errorDiv = document.getElementById('nombreError');
        if (nombre.length <= 4) {
            errorDiv.style.display = 'block'; // Muestra el mensaje de error
            return false; // Evita que el formulario se envíe
        } else {
            errorDiv.style.display = 'none'; // Oculta el mensaje de error si todo está correcto
            return true; // Permite que el formulario se envíe
        }
    }
