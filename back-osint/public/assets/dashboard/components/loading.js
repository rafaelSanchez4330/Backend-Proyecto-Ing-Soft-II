/**
 * Sistema de Indicadores de Carga
 */

class Loading {
    constructor() {
        this.overlay = null;
        this.activeLoaders = new Set();
    }

    /**
     * Mostrar overlay de carga
     * @param {string} message - Mensaje opcional
     */
    show(message = 'Cargando...') {
        if (!this.overlay) {
            const overlayHTML = `
        <div class="loading-overlay" id="loadingOverlay">
          <div class="loading-content">
            <div class="loading-spinner">
              <div class="spinner-ring"></div>
              <div class="spinner-ring"></div>
              <div class="spinner-ring"></div>
            </div>
            <p class="loading-message" id="loadingMessage">${message}</p>
          </div>
        </div>
      `;

            document.body.insertAdjacentHTML('beforeend', overlayHTML);
            this.overlay = document.getElementById('loadingOverlay');

            requestAnimationFrame(() => {
                this.overlay.classList.add('loading-active');
            });

            document.body.style.overflow = 'hidden';
        } else {
            // Actualizar mensaje si ya existe
            const messageEl = document.getElementById('loadingMessage');
            if (messageEl) {
                messageEl.textContent = message;
            }
        }
    }

    /**
     * Ocultar overlay de carga
     */
    hide() {
        if (this.overlay) {
            this.overlay.classList.remove('loading-active');

            setTimeout(() => {
                if (this.overlay && this.overlay.parentNode) {
                    this.overlay.parentNode.removeChild(this.overlay);
                }
                this.overlay = null;
                document.body.style.overflow = '';
            }, 300);
        }
    }

    /**
     * Crear spinner inline
     * @param {string} size - Tamaño: 'small', 'medium', 'large'
     * @returns {HTMLElement}
     */
    createSpinner(size = 'medium') {
        const spinner = document.createElement('div');
        spinner.className = `loading-spinner-inline loading-spinner-${size}`;
        spinner.innerHTML = `
      <div class="spinner-ring"></div>
      <div class="spinner-ring"></div>
      <div class="spinner-ring"></div>
    `;
        return spinner;
    }

    /**
     * Agregar spinner a un elemento
     * @param {HTMLElement} element - Elemento donde agregar el spinner
     * @param {string} size - Tamaño del spinner
     */
    addSpinner(element, size = 'medium') {
        if (!element) return null;

        const spinnerId = `spinner-${Date.now()}`;
        const spinner = this.createSpinner(size);
        spinner.id = spinnerId;

        element.appendChild(spinner);
        this.activeLoaders.add(spinnerId);

        return spinnerId;
    }

    /**
     * Remover spinner de un elemento
     * @param {string} spinnerId - ID del spinner
     */
    removeSpinner(spinnerId) {
        const spinner = document.getElementById(spinnerId);
        if (spinner && spinner.parentNode) {
            spinner.parentNode.removeChild(spinner);
            this.activeLoaders.delete(spinnerId);
        }
    }

    /**
     * Crear skeleton loader
     * @param {number} lines - Número de líneas
     * @returns {HTMLElement}
     */
    createSkeleton(lines = 3) {
        const skeleton = document.createElement('div');
        skeleton.className = 'skeleton-loader';

        for (let i = 0; i < lines; i++) {
            const line = document.createElement('div');
            line.className = 'skeleton-line';
            if (i === lines - 1) {
                line.style.width = '60%';
            }
            skeleton.appendChild(line);
        }

        return skeleton;
    }

    /**
     * Mostrar estado de carga en un botón
     * @param {HTMLElement} button - Elemento del botón
     * @param {boolean} loading - Estado de carga
     */
    buttonLoading(button, loading = true) {
        if (!button) return;

        if (loading) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `
        <span class="button-spinner">
          <div class="spinner-ring"></div>
          <div class="spinner-ring"></div>
          <div class="spinner-ring"></div>
        </span>
        <span>Cargando...</span>
      `;
            button.classList.add('button-loading');
        } else {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText || button.innerHTML;
            button.classList.remove('button-loading');
            delete button.dataset.originalText;
        }
    }

    /**
     * Ejecutar función con loading overlay
     * @param {Function} fn - Función async a ejecutar
     * @param {string} message - Mensaje de carga
     */
    async withLoading(fn, message = 'Cargando...') {
        this.show(message);
        try {
            const result = await fn();
            return result;
        } finally {
            this.hide();
        }
    }
}

// Exportar instancia única
const loading = new Loading();
window.loading = loading;
