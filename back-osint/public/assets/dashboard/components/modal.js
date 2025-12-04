/**
 * Sistema de Modales Reutilizables
 */

class Modal {
    constructor() {
        this.activeModal = null;
    }

    /**
     * Crear y mostrar modal
     * @param {Object} options - Configuración del modal
     * @param {string} options.title - Título del modal
     * @param {string} options.content - Contenido HTML del modal
     * @param {string} options.size - Tamaño: 'small', 'medium', 'large'
     * @param {Array} options.buttons - Array de botones {text, class, onClick}
     * @param {boolean} options.closeOnBackdrop - Cerrar al hacer clic fuera
     */
    show(options = {}) {
        console.log('Modal.show iniciado', options);
        const {
            title = '',
            content = '',
            size = 'medium',
            buttons = [],
            closeOnBackdrop = true
        } = options;

        // Cerrar modal existente inmediatamente si hay uno
        console.log('Intentando cerrar modal previo...');
        this.close(true);

        // Crear estructura del modal
        const modalHTML = `
      <div class="app-modal-overlay" id="modalOverlay">
        <div class="app-modal-container app-modal-${size}">
          <div class="app-modal-header">
            <h3 class="app-modal-title">${title}</h3>
            <button class="app-modal-close" id="modalClose" aria-label="Cerrar">
              <svg viewBox="0 0 24 24" width="20" height="20">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
              </svg>
            </button>
          </div>
          <div class="app-modal-body">
            ${content}
          </div>
          ${buttons.length > 0 ? `
            <div class="app-modal-footer">
              ${buttons.map((btn, index) => `
                <button class="app-modal-btn ${btn.class ? 'app-' + btn.class : 'app-modal-btn-secondary'}" data-btn-index="${index}">
                  ${btn.text}
                </button>
              `).join('')}
            </div>
          ` : ''}
        </div>
      </div>
    `;

        // Insertar en el DOM
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.activeModal = document.getElementById('modalOverlay');

        // Debug styles
        const styles = window.getComputedStyle(this.activeModal);
        console.log('Modal Styles Debug:', {
            zIndex: styles.zIndex,
            opacity: styles.opacity,
            display: styles.display,
            visibility: styles.visibility,
            position: styles.position
        });

        console.log('Modal insertado en DOM:', this.activeModal);

        // Event listeners
        const closeBtn = document.getElementById('modalClose');
        closeBtn.addEventListener('click', () => this.close());

        if (closeOnBackdrop) {
            this.activeModal.addEventListener('click', (e) => {
                if (e.target === this.activeModal) {
                    this.close();
                }
            });
        }

        // Event listeners para botones personalizados
        buttons.forEach((btn, index) => {
            const btnElement = this.activeModal.querySelector(`[data-btn-index="${index}"]`);
            if (btnElement && btn.onClick) {
                btnElement.addEventListener('click', () => {
                    btn.onClick();
                    if (btn.closeOnClick !== false) {
                        this.close();
                    }
                });
            }
        });

        // Animación de entrada
        requestAnimationFrame(() => {
            if (this.activeModal) {
                this.activeModal.classList.add('app-modal-active');
            }
        });

        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';

        return this.activeModal;
    }

    /**
     * Cerrar modal activo
     * @param {boolean} immediate - Cerrar sin animación
     */
    close(immediate = false) {
        if (this.activeModal) {
            this.activeModal.classList.remove('app-modal-active');

            const remove = () => {
                if (this.activeModal && this.activeModal.parentNode) {
                    this.activeModal.parentNode.removeChild(this.activeModal);
                }
                this.activeModal = null;
                document.body.style.overflow = '';
            };

            if (immediate) {
                remove();
            } else {
                setTimeout(remove, 300);
            }
        }
    }

    /**
     * Mostrar modal de confirmación
     * @param {string} message - Mensaje de confirmación
     * @param {Function} onConfirm - Callback al confirmar
     * @param {Function} onCancel - Callback al cancelar
     */
    confirm(message, onConfirm, onCancel) {
        this.show({
            title: 'Confirmación',
            content: `<p class="app-modal-confirm-message">${message}</p>`,
            size: 'small',
            buttons: [
                {
                    text: 'Cancelar',
                    class: 'modal-btn-secondary',
                    onClick: () => {
                        if (onCancel) onCancel();
                    }
                },
                {
                    text: 'Confirmar',
                    class: 'modal-btn-primary',
                    onClick: () => {
                        if (onConfirm) onConfirm();
                    }
                }
            ]
        });
    }

    /**
     * Mostrar modal de alerta
     * @param {string} message - Mensaje de alerta
     * @param {string} type - Tipo: 'success', 'error', 'warning', 'info'
     */
    alert(message, type = 'info') {
        const icons = {
            success: '<svg viewBox="0 0 24 24" width="48" height="48" fill="#10b981"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
            error: '<svg viewBox="0 0 24 24" width="48" height="48" fill="#ef4444"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>',
            warning: '<svg viewBox="0 0 24 24" width="48" height="48" fill="#f59e0b"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>',
            info: '<svg viewBox="0 0 24 24" width="48" height="48" fill="#3b82f6"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>'
        };

        this.show({
            title: type.charAt(0).toUpperCase() + type.slice(1),
            content: `
        <div class="app-modal-alert app-modal-alert-${type}">
          <div class="app-modal-alert-icon">${icons[type]}</div>
          <p class="app-modal-alert-message">${message}</p>
        </div>
      `,
            size: 'small',
            buttons: [
                {
                    text: 'Aceptar',
                    class: 'modal-btn-primary',
                    onClick: () => { }
                }
            ]
        });
    }
}

// Exportar instancia única
const modal = new Modal();
window.modal = modal;
