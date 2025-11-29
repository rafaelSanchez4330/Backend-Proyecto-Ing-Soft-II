document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('userMenuToggle');
  const menu = document.getElementById('userMenu');

  if (!toggle || !menu) return;

  function cerrarMenu() {
    menu.classList.remove('abierto');
    toggle.setAttribute('aria-expanded', 'false');
  }

  function alternarMenu() {
    const abierto = menu.classList.toggle('abierto');
    toggle.setAttribute('aria-expanded', abierto ? 'true' : 'false');
  }

  toggle.addEventListener('click', (event) => {
    event.stopPropagation();
    alternarMenu();
  });

  document.addEventListener('click', (event) => {
    if (!menu.classList.contains('abierto')) return;
    if (!menu.contains(event.target) && !toggle.contains(event.target)) {
      cerrarMenu();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      cerrarMenu();
    }
  });
});
