/**
 * Lógica para la vista de detalle de caso
 */

document.addEventListener('DOMContentLoaded', function () {
    // Manejo de tabs
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const tabName = this.dataset.tab;

            // Actualizar botones activos
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Actualizar contenido activo
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === `tab-${tabName}`) {
                    content.classList.add('active');
                }
            });

            // Si cambiamos a la tab de reportes, cargar reportes
            if (tabName === 'reportes') {
                cargarReportes();
            }
        });
    });

    // Guardar token de autenticación si existe
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        // El token CSRF de Laravel no es el mismo que el Bearer token de la API
        // Necesitamos obtener el token de la API del localStorage o de la sesión
        // Por ahora, asumimos que ya está configurado
    }
});
