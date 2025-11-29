document.addEventListener('DOMContentLoaded', () => {
  // ====== MODELO EN MEMORIA ======
  const casos = [
    { id: 'UPSLP-124012024', titulo: 'Intento de phishing en correo institucional', estado: 'Active', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' },
    { id: 'UPSLP-224012024', titulo: 'Ataque de ransomware a servidor CADI', estado: 'Active', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' },
    { id: 'UPSLP-324012024', titulo: 'Operativo de búsqueda de eventos de riesgo', estado: 'In progress', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' },
    { id: 'UPSLP-424012024', titulo: 'Ataque DDoS a página institucional', estado: 'Active', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' },
    { id: 'UPSLP-524012024', titulo: 'Investigación de fuga de credenciales', estado: 'In progress', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' },
    { id: 'UPSLP-624012024', titulo: 'Cierre de incidente de suplantación de identidad', estado: 'Closed', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' },
    { id: 'UPSLP-724012024', titulo: 'Análisis forense de equipo comprometido', estado: 'Closed', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' },
    { id: 'UPSLP-824012024', titulo: 'Monitoreo de actividad sospechosa en red interna', estado: 'Active', notas: '', encargado: '', inicioReporte: '', inicioAsignacion: '', finalizacion: '' }
  ];
  
  const evidenciasPorCaso = new Map();
  const usuarios = [];
  const reportes = [];
  
  // ====== UTILIDADES ======
  function claseEstado(estado) {
    if (estado === 'Active') return 'estado-activo';
    if (estado === 'In progress') return 'estado-progreso';
    return 'estado-finalizado';
  }
  
  function buscarCasoPorId(id) {
    return casos.find((c) => c.id === id);
  }
  
  function generarIdCaso() {
    const prefijo = 'UPSLP-';
    let maxNum = 0;
    casos.forEach((c) => {
      const m = c.id.match(/^UPSLP-(\d{3})/);
      if (m) {
        const n = parseInt(m[1], 10);
        if (!Number.isNaN(n) && n > maxNum) maxNum = n;
      }
    });
    const siguiente = (maxNum || 0) + 1;
    const numeroStr = String(siguiente).padStart(3, '0');
    const hoy = new Date();
    const dd = String(hoy.getDate()).padStart(2, '0');
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const yyyy = hoy.getFullYear();
    const fechaStr = `${dd}${mm}${yyyy}`;
    return `${prefijo}${numeroStr}${fechaStr}`;
  }
  
  function agregarEvidencias(idCaso, fileList) {
    if (!idCaso || !fileList || !fileList.length) return;
    const ahora = new Date().toISOString();
    const arr = evidenciasPorCaso.get(idCaso) || [];
    Array.from(fileList).forEach((file) => {
      arr.push({ nombre: file.name, tipo: file.type || 'archivo', fecha: ahora });
    });
    evidenciasPorCaso.set(idCaso, arr);
  }
  
  // ====== DOM BÁSICO ======
  const slider = document.getElementById('casosSlider');
  const btnPrev = document.getElementById('casosPrev');
  const btnNext = document.getElementById('casosNext');
  const track = document.querySelector('.casos-track');
  
  const btnAdmin = document.getElementById('btnAdmin');
  const btnNewCase = document.getElementById('btnNewCase');
  const btnNewUser = document.getElementById('btnNewUser');
  const btnEvidence = document.getElementById('btnEvidence');
  const btnReport = document.getElementById('btnReport');
  
  const modalEditar = document.getElementById('modalEditarCaso');
  const modalNuevo = document.getElementById('modalNuevoCaso');
  const modalNuevoUsuario = document.getElementById('modalNuevoUsuario');
  const modalEvidencias = document.getElementById('modalEvidencias');
  const modalReporte = document.getElementById('modalReporte');
  const modalAdmin = document.getElementById('modalAdmin');
  
  // Campos modal editar caso
  const campoEstado = document.getElementById('campoEstado');
  const campoId = document.getElementById('campoId');
  const campoTitulo = document.getElementById('campoTitulo');
  const campoNotas = document.getElementById('campoNotas');
  const campoEncargado = document.getElementById('campoEncargado');
  const campoInicioReporte = document.getElementById('campoInicioReporte');
  const campoInicioAsignacion = document.getElementById('campoInicioAsignacion');
  const campoFinalizacion = document.getElementById('campoFinalizacion');
  const campoEvidenciaEditar = document.getElementById('campoEvidenciaEditar');
  const badgeEstadoActual = document.getElementById('badgeEstadoActual');
  const btnGuardarCaso = document.getElementById('btnGuardarCaso');
  
  // Campos modal nuevo caso
  const nuevoPlantilla = document.getElementById('nuevoPlantilla');
  const nuevoTitulo = document.getElementById('nuevoTitulo');
  const nuevoEstado = document.getElementById('nuevoEstado');
  const nuevoEncargado = document.getElementById('nuevoEncargado');
  const nuevoNotas = document.getElementById('nuevoNotas');
  const nuevoInicioReporte = document.getElementById('nuevoInicioReporte');
  const nuevoInicioAsignacion = document.getElementById('nuevoInicioAsignacion');
  const nuevoFinalizacion = document.getElementById('nuevoFinalizacion');
  const nuevoEvidencia = document.getElementById('nuevoEvidencia');
  const btnCrearCaso = document.getElementById('btnCrearCaso');
  
  // Campos modal nuevo usuario
  const nuevoNombreUsuario = document.getElementById('nuevoNombreUsuario');
  const nuevoCorreoUsuario = document.getElementById('nuevoCorreoUsuario');
  const nuevoPasswordUsuario = document.getElementById('nuevoPasswordUsuario');
  const nuevoCelularUsuario = document.getElementById('nuevoCelularUsuario');
  const btnCrearUsuario = document.getElementById('btnCrearUsuario');
  
  // Evidencias
  const tablaEvidenciasBody = document.getElementById('tablaEvidenciasBody');
  
  // Reporte
  const reporteTitulo = document.getElementById('reporteTitulo');
  const reporteCaso = document.getElementById('reporteCaso');
  const reporteTipo = document.getElementById('reporteTipo');
  const reporteSeveridad = document.getElementById('reporteSeveridad');
  const reporteResumen = document.getElementById('reporteResumen');
  const reporteDetalle = document.getElementById('reporteDetalle');
  const reporteAdjuntos = document.getElementById('reporteAdjuntos');
  const btnCrearReporte = document.getElementById('btnCrearReporte');
  
  // Admin
  const adminUsuariosLista = document.getElementById('adminUsuariosLista');
  const adminCasosLista = document.getElementById('adminCasosLista');
  
  let idCasoEditando = null;
  
  // ====== MODALES: HELPERS ======
  const overlays = document.querySelectorAll('.modal-overlay');
  
  function abrirModal(modal) {
    if (!modal) return;
    modal.classList.add('activo');
  }
  
  function cerrarModal(modal) {
    if (!modal) return;
    modal.classList.remove('activo');
  }
  
  overlays.forEach((overlay) => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) {
        overlay.classList.remove('activo');
      }
    });
  });
  
  document.querySelectorAll('[data-modal-close]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const overlay = btn.closest('.modal-overlay');
      if (overlay) overlay.classList.remove('activo');
    });
  });
  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      overlays.forEach((o) => o.classList.remove('activo'));
    }
  });
  
  function actualizarBadgeEstado(estado) {
    if (!badgeEstadoActual) return;
    badgeEstadoActual.textContent = estado;
    badgeEstadoActual.classList.remove('estado-activo', 'estado-progreso', 'estado-finalizado');
    badgeEstadoActual.classList.add(claseEstado(estado));
  }
  
  // ====== EVENT LISTENERS PARA BOTONES DEL PANEL ======
  if (btnAdmin) {
    btnAdmin.addEventListener('click', () => {
      renderAdminPanel();
      abrirModal(modalAdmin);
    });
  }
  
  if (btnNewCase) {
    btnNewCase.addEventListener('click', () => {
      limpiarFormularioNuevoCaso();
      abrirModal(modalNuevo);
    });
  }
  
  if (btnNewUser) {
    btnNewUser.addEventListener('click', () => {
      limpiarFormularioNuevoUsuario();
      abrirModal(modalNuevoUsuario);
    });
  }
  
  if (btnEvidence) {
    btnEvidence.addEventListener('click', () => {
      renderTablaEvidencias();
      abrirModal(modalEvidencias);
    });
  }
  
  if (btnReport) {
    btnReport.addEventListener('click', () => {
      limpiarFormularioReporte();
      actualizarSelectCasosReporte();
      abrirModal(modalReporte);
    });
  }
  
  // ====== RENDER DE CASOS ======
  function crearTarjetaCaso(caso) {
    const article = document.createElement('article');
    article.className = 'caso-card';
    article.dataset.caseId = caso.id;
    article.innerHTML = `
      <div class="caso-header">
        <span class="estado-badge ${claseEstado(caso.estado)}">
          <span class="estado-icono"></span>
          ${caso.estado}
        </span>
      </div>
      <div class="caso-body">
        <p>${caso.titulo}</p>
      </div>
      <div class="caso-footer">
        <span>ID:</span>${caso.id}
      </div>
    `;
    article.addEventListener('click', () => {
      abrirEdicionCaso(caso.id);
    });
    return article;
  }
  
  function renderCasos() {
    if (!track) return;
    track.innerHTML = '';
    casos.forEach((caso) => {
      track.appendChild(crearTarjetaCaso(caso));
    });
  }
  
  // ====== CARRUSEL ======
  if (slider && btnPrev && btnNext) {
    let isDragging = false;
    let startX = 0;
    let scrollLeft = 0;
    
    slider.addEventListener('mousedown', (e) => {
      isDragging = true;
      slider.classList.add('arrastrando');
      startX = e.pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
    });
    
    slider.addEventListener('mouseleave', () => {
      isDragging = false;
      slider.classList.remove('arrastrando');
    });
    
    slider.addEventListener('mouseup', () => {
      isDragging = false;
      slider.classList.remove('arrastrando');
    });
    
    slider.addEventListener('mousemove', (e) => {
      if (!isDragging) return;
      e.preventDefault();
      const x = e.pageX - slider.offsetLeft;
      const walk = (x - startX) * 1.5;
      slider.scrollLeft = scrollLeft - walk;
    });
    
    btnPrev.addEventListener('click', () => {
      slider.scrollBy({ left: -280, behavior: 'smooth' });
    });
    
    btnNext.addEventListener('click', () => {
      slider.scrollBy({ left: 280, behavior: 'smooth' });
    });
  }
  
  // ====== MODAL EDITAR CASO ======
  function abrirEdicionCaso(id) {
    const caso = buscarCasoPorId(id);
    if (!caso) return;
    idCasoEditando = id;
    if (campoId) campoId.value = caso.id;
    if (campoTitulo) campoTitulo.value = caso.titulo;
    if (campoNotas) campoNotas.value = caso.notas || '';
    if (campoEncargado) campoEncargado.value = caso.encargado || '';
    if (campoInicioReporte) campoInicioReporte.value = caso.inicioReporte || '';
    if (campoInicioAsignacion) campoInicioAsignacion.value = caso.inicioAsignacion || '';
    if (campoFinalizacion) campoFinalizacion.value = caso.finalizacion || '';
    if (campoEstado) campoEstado.value = caso.estado;
    actualizarBadgeEstado(caso.estado);
    abrirModal(modalEditar);
  }
  
  if (campoEstado) {
    campoEstado.addEventListener('change', (e) => {
      actualizarBadgeEstado(e.target.value);
    });
  }
  
  if (btnGuardarCaso) {
    btnGuardarCaso.addEventListener('click', () => {
      if (!idCasoEditando) return;
      const caso = buscarCasoPorId(idCasoEditando);
      if (!caso) return;
      caso.titulo = campoTitulo.value.trim();
      caso.estado = campoEstado.value;
      caso.notas = campoNotas.value.trim();
      caso.encargado = campoEncargado.value.trim();
      caso.inicioReporte = campoInicioReporte.value;
      caso.inicioAsignacion = campoInicioAsignacion.value;
      caso.finalizacion = campoFinalizacion.value;
      if (campoEvidenciaEditar && campoEvidenciaEditar.files.length > 0) {
        agregarEvidencias(idCasoEditando, campoEvidenciaEditar.files);
      }
      renderCasos();
      cerrarModal(modalEditar);
      idCasoEditando = null;
    });
  }
  
  // ====== MODAL NUEVO CASO ======
  function limpiarFormularioNuevoCaso() {
    if (nuevoPlantilla) nuevoPlantilla.value = '';
    if (nuevoTitulo) nuevoTitulo.value = '';
    if (nuevoEstado) nuevoEstado.value = 'Active';
    if (nuevoEncargado) nuevoEncargado.value = '';
    if (nuevoNotas) nuevoNotas.value = '';
    if (nuevoInicioReporte) nuevoInicioReporte.value = '';
    if (nuevoInicioAsignacion) nuevoInicioAsignacion.value = '';
    if (nuevoFinalizacion) nuevoFinalizacion.value = '';
    if (nuevoEvidencia) nuevoEvidencia.value = '';
  }
  
  if (nuevoPlantilla) {
    nuevoPlantilla.addEventListener('change', () => {
      const id = nuevoPlantilla.value;
      if (!id) return;
      const caso = buscarCasoPorId(id);
      if (!caso) return;
      if (nuevoTitulo) nuevoTitulo.value = caso.titulo;
      if (nuevoEstado) nuevoEstado.value = caso.estado;
      if (nuevoEncargado) nuevoEncargado.value = caso.encargado || '';
      if (nuevoNotas) nuevoNotas.value = caso.notas || '';
    });
  }
  
  if (btnCrearCaso) {
    btnCrearCaso.addEventListener('click', () => {
      const titulo = nuevoTitulo.value.trim();
      if (!titulo) {
        alert('El título es obligatorio');
        return;
      }
      const newId = generarIdCaso();
      const nuevoCaso = {
        id: newId,
        titulo,
        estado: nuevoEstado.value,
        notas: nuevoNotas.value.trim(),
        encargado: nuevoEncargado.value.trim(),
        inicioReporte: nuevoInicioReporte.value,
        inicioAsignacion: nuevoInicioAsignacion.value,
        finalizacion: nuevoFinalizacion.value
      };
      if (nuevoEvidencia && nuevoEvidencia.files.length > 0) {
        agregarEvidencias(newId, nuevoEvidencia.files);
      }
      casos.push(nuevoCaso);
      renderCasos();
      cerrarModal(modalNuevo);
    });
  }
  
  // ====== MODAL NUEVO USUARIO ======
  function limpiarFormularioNuevoUsuario() {
    if (nuevoNombreUsuario) nuevoNombreUsuario.value = '';
    if (nuevoCorreoUsuario) nuevoCorreoUsuario.value = '';
    if (nuevoPasswordUsuario) nuevoPasswordUsuario.value = '';
    if (nuevoCelularUsuario) nuevoCelularUsuario.value = '';
  }
  
  if (btnCrearUsuario) {
    btnCrearUsuario.addEventListener('click', () => {
      const nombre = nuevoNombreUsuario.value.trim();
      const correo = nuevoCorreoUsuario.value.trim();
      const password = nuevoPasswordUsuario.value.trim();
      const celular = nuevoCelularUsuario.value.trim();
      if (!nombre || !correo || !password) {
        alert('Nombre, correo y contraseña son obligatorios');
        return;
      }
      usuarios.push({ nombre, correo, password, celular });
      cerrarModal(modalNuevoUsuario);
      alert('Usuario creado correctamente');
    });
  }
  
  // ====== MODAL EVIDENCIAS ======
  function renderTablaEvidencias() {
    if (!tablaEvidenciasBody) return;
    tablaEvidenciasBody.innerHTML = '';
    if (evidenciasPorCaso.size === 0) {
      tablaEvidenciasBody.innerHTML = `
        <tr>
          <td colspan="4" style="text-align:center;padding:1.2rem;opacity:0.7;">
            No hay evidencias registradas
          </td>
        </tr>
      `;
      return;
    }
    evidenciasPorCaso.forEach((evs, idCaso) => {
      const caso = buscarCasoPorId(idCaso);
      const titulo = caso ? caso.titulo : 'Caso no encontrado';
      evs.forEach((ev) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><span class="badge-case-id">${idCaso}</span></td>
          <td>${titulo}</td>
          <td>${ev.nombre}</td>
          <td>${new Date(ev.fecha).toLocaleString()}</td>
        `;
        tablaEvidenciasBody.appendChild(tr);
      });
    });
  }
  
  // ====== MODAL REPORTE ======
  function limpiarFormularioReporte() {
    if (reporteTitulo) reporteTitulo.value = '';
    if (reporteCaso) reporteCaso.value = '';
    if (reporteTipo) reporteTipo.value = 'Incidente';
    if (reporteSeveridad) reporteSeveridad.value = 'Baja';
    if (reporteResumen) reporteResumen.value = '';
    if (reporteDetalle) reporteDetalle.value = '';
    if (reporteAdjuntos) reporteAdjuntos.value = '';
  }
  
  function actualizarSelectCasosReporte() {
    if (!reporteCaso) return;
    reporteCaso.innerHTML = '<option value="">Selecciona un caso...</option>';
    casos.forEach((caso) => {
      const opt = document.createElement('option');
      opt.value = caso.id;
      opt.textContent = `${caso.id} - ${caso.titulo}`;
      reporteCaso.appendChild(opt);
    });
  }
  
  if (btnCrearReporte) {
    btnCrearReporte.addEventListener('click', () => {
      const titulo = reporteTitulo.value.trim();
      const casoId = reporteCaso.value;
      if (!titulo || !casoId) {
        alert('Título y caso relacionado son obligatorios');
        return;
      }
      const nuevoReporte = {
        titulo,
        casoId,
        tipo: reporteTipo.value,
        severidad: reporteSeveridad.value,
        resumen: reporteResumen.value.trim(),
        detalle: reporteDetalle.value.trim()
      };
      reportes.push(nuevoReporte);
      cerrarModal(modalReporte);
      alert('Reporte creado correctamente');
    });
  }
  
  // ====== MODAL ADMIN ======
  function renderAdminPanel() {
    if (adminUsuariosLista) {
      adminUsuariosLista.innerHTML = '';
      if (usuarios.length === 0) {
        adminUsuariosLista.innerHTML = `
          <li style="padding:0.5rem;text-align:center;opacity:0.7;">
            No hay usuarios registrados
          </li>
        `;
      } else {
        usuarios.forEach((u) => {
          const li = document.createElement('li');
          li.className = 'admin-usuario-item';
          li.innerHTML = `
            <span>${u.nombre}</span>
            <span style="opacity:0.7;">${u.correo}</span>
          `;
          adminUsuariosLista.appendChild(li);
        });
      }
    }
    if (adminCasosLista) {
      adminCasosLista.innerHTML = '';
      if (casos.length === 0) {
        adminCasosLista.innerHTML = `
          <div style="padding:0.5rem;text-align:center;opacity:0.7;">
            No hay casos
          </div>
        `;
      } else {
        casos.forEach((caso) => {
          const div = document.createElement('div');
          div.className = 'admin-caso-item';
          div.innerHTML = `
            <label class="admin-caso-label">${caso.id} - ${caso.titulo}</label>
            <select class="admin-caso-select" data-case-id="${caso.id}">
              <option value="Active" ${caso.estado === 'Active' ? 'selected' : ''}>Active</option>
              <option value="In progress" ${caso.estado === 'In progress' ? 'selected' : ''}>In progress</option>
              <option value="Closed" ${caso.estado === 'Closed' ? 'selected' : ''}>Closed</option>
            </select>
          `;
          adminCasosLista.appendChild(div);
        });
        document.querySelectorAll('.admin-caso-select').forEach((select) => {
          select.addEventListener('change', (e) => {
            const id = e.target.dataset.caseId;
            const nuevoEstado = e.target.value;
            const caso = buscarCasoPorId(id);
            if (caso) {
              caso.estado = nuevoEstado;
              renderCasos();
            }
          });
        });
      }
    }
  }
  
  // ====== INICIALIZAR ======
  renderCasos();
});
