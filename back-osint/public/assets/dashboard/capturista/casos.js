/**
 * Lógica para la vista de Casos
 */

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchCasos');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const casosGrid = document.getElementById('casosGrid');
    const casoCards = document.querySelectorAll('.caso-card-link');

    let currentFilter = 'todos';
    let searchTerm = '';

    /**
     * Filtrar casos
     */
    function filterCasos() {
        let visibleCount = 0;

        casoCards.forEach(card => {
            const estado = card.dataset.estado;
            const cardText = card.textContent.toLowerCase();

            // Verificar filtro de estado
            const matchesFilter = currentFilter === 'todos' || estado === currentFilter;

            // Verificar búsqueda
            const matchesSearch = searchTerm === '' || cardText.includes(searchTerm.toLowerCase());

            if (matchesFilter && matchesSearch) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Mostrar mensaje si no hay resultados
        showEmptyState(visibleCount === 0);
    }

    /**
     * Mostrar/ocultar estado vacío
     */
    function showEmptyState(show) {
        let emptyState = casosGrid.querySelector('.empty-state');

        if (show && !emptyState) {
            const emptyHTML = `
        <div class="empty-state" style="grid-column: 1 / -1;">
          <div class="empty-state-icon">
            <svg viewBox="0 0 24 24">
              <path d="M10 2a8 8 0 1 1-5.3 14l-3.1 3.1-1.4-1.4 3.1-3.1A8 8 0 0 1 10 2Zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12Z" />
            </svg>
          </div>
          <h3 class="empty-state-title">No se encontraron casos</h3>
          <p class="empty-state-description">Intenta ajustar los filtros o el término de búsqueda.</p>
        </div>
      `;
            casosGrid.insertAdjacentHTML('beforeend', emptyHTML);
        } else if (!show && emptyState) {
            emptyState.remove();
        }
    }

    /**
     * Event listeners para filtros
     */
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Actualizar botones activos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Actualizar filtro actual
            currentFilter = this.dataset.filter;

            // Aplicar filtros
            filterCasos();
        });
    });

    /**
     * Event listener para búsqueda
     */
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            searchTerm = this.value;
            filterCasos();
        });
    }

    /**
     * Animación de entrada para las tarjetas
     */
    function animateCards() {
        casoCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

                requestAnimationFrame(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            }, index * 50);
        });
    }

    // Ejecutar animación al cargar
    if (casoCards.length > 0) {
        animateCards();
    }
});
