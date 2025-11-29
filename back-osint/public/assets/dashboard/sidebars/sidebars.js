document.addEventListener('DOMContentLoaded', () => {
  const sidebarIzquierda = document.getElementById('sidebarIzquierda');
  const sidebarDerecha = document.getElementById('sidebarDerecha');
  const toggleIzquierda = document.querySelector('.sidebar-toggle-izquierda');
  const toggleDerecha = document.querySelector('.sidebar-toggle-derecha');

  if (!sidebarIzquierda || !sidebarDerecha || !toggleIzquierda || !toggleDerecha) return;

  function alternarSidebar(sidebar, toggle, esIzquierda) {
    const colapsada = sidebar.classList.toggle('colapsada');

    if (esIzquierda) {
      toggle.innerHTML = colapsada ? '&#x203A;' : '&#x2039;';
      toggle.setAttribute('aria-label', colapsada ? 'Mostrar barra izquierda' : 'Ocultar barra izquierda');
    } else {
      toggle.innerHTML = colapsada ? '&#x2039;' : '&#x203A;';
      toggle.setAttribute('aria-label', colapsada ? 'Mostrar barra derecha' : 'Ocultar barra derecha');
    }
  }

  toggleIzquierda.addEventListener('click', () => {
    alternarSidebar(sidebarIzquierda, toggleIzquierda, true);
  });

  toggleDerecha.addEventListener('click', () => {
    alternarSidebar(sidebarDerecha, toggleDerecha, false);
  });
});
