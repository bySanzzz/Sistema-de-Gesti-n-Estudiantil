document.getElementById('openMenu').addEventListener('click', function() {
    document.querySelector('.nav-list').classList.add('open');  // Agrega la clase 'open' para mostrar el menú
    this.style.visibility = 'hidden';  // Oculta el botón "Abrir" manteniendo el espacio
    document.getElementById('closeMenu').style.visibility = 'visible';  // Muestra el botón "Cerrar"
});

document.getElementById('closeMenu').addEventListener('click', function() {
    document.querySelector('.nav-list').classList.remove('open');  // Quita la clase 'open' para ocultar el menú
    this.style.visibility = 'hidden';  // Oculta el botón "Cerrar"
    document.getElementById('openMenu').style.visibility = 'visible';  // Muestra el botón "Abrir"
});
