/**
 * Sistema de Notificaciones Toast
 */

class Toast {
    constructor() {
        this.container = null;
        this.toasts = [];
        this.init();
    }

    /**
     * Inicializar contenedor de toasts
     */
    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            this.container.id = 'toastContainer';
            document.body.appendChild(this.container);
        }
    }

    /**
     * Mostrar notificación toast
     * @param {string} message - Mensaje a mostrar
     * @param {string} type - Tipo: 'success', 'error', 'warning', 'info'
     * @param {number} duration - Duración en ms (0 = no auto-dismiss)
     */
    show(message, type = 'info', duration = 4000) {
        const icons = {
            success: '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
            error: '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',
            warning: '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>',
            info: '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>'
        };

        const toastId = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

        const toastHTML = `
      <div class="toast toast-${type}" id="${toastId}">
        <div class="toast-icon">
          ${icons[type]}
        </div>
        <div class="toast-message">${message}</div>
        <button class="toast-close" aria-label="Cerrar">
          <svg viewBox="0 0 24 24" width="16" height="16">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </button>
      </div>
    `;

        this.container.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);

        // Agregar a la lista
        this.toasts.push(toastElement);

        // Event listener para cerrar
        const closeBtn = toastElement.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => this.remove(toastElement));

        // Animación de entrada
        requestAnimationFrame(() => {
            toastElement.classList.add('toast-show');
        });

        // Auto-dismiss si duration > 0
        if (duration > 0) {
            setTimeout(() => {
                this.remove(toastElement);
            }, duration);
        }

        return toastElement;
    }

    /**
     * Remover toast
     */
    remove(toastElement) {
        if (!toastElement) return;

        toastElement.classList.remove('toast-show');
        toastElement.classList.add('toast-hide');

        setTimeout(() => {
            if (toastElement.parentNode) {
                toastElement.parentNode.removeChild(toastElement);
            }
            this.toasts = this.toasts.filter(t => t !== toastElement);
        }, 300);
    }

    /**
     * Métodos de conveniencia
     */
    success(message, duration = 4000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 4500) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 4000) {
        return this.show(message, 'info', duration);
    }

    /**
     * Limpiar todos los toasts
     */
    clearAll() {
        this.toasts.forEach(toast => this.remove(toast));
    }
}

// Exportar instancia única
const toast = new Toast();
window.toast = toast;
