document.addEventListener('DOMContentLoaded', () => {

  // ==========================================
  // 1. ESTADO DE LA APLICACIÓN
  // ==========================================
  let casos = [];
  let rolUsuario = 'invitado'; // Default role

  // ==========================================
  // 2. REFERENCIAS AL DOM
  // ==========================================
  const track = document.querySelector('.casos-track');
  const slider = document.getElementById('casosSlider');
  const btnPrev = document.getElementById('casosPrev');
  const btnNext = document.getElementById('casosNext');

  const btnAdmin = document.getElementById('btnAdmin');
  const btnNewCase = document.getElementById('btnNewCase');
  const btnNewUser = document.getElementById('btnNewUser');
  const btnEvidence = document.getElementById('btnEvidence');
  const btnReport = document.getElementById('btnReport');

  // Modales existentes (Menú lateral)
  const modalEditar = document.getElementById('modalEditarCaso');
  const modalNuevo = document.getElementById('modalNuevoCaso');
  const modalNuevoUsuario = document.getElementById('modalNuevoUsuario');
  const modalEvidencias = document.getElementById('modalEvidencias');
  const modalReporte = document.getElementById('modalReporte');
  const modalAdmin = document.getElementById('modalAdmin');
  const modalGestionCasos = document.getElementById('modalGestionCasos');

  // Botones dentro de modales
  const btnNuevoCasoDesdeGestion = document.getElementById('btnNuevoCasoDesdeGestion');
  const btnCrearCaso = document.getElementById('btnCrearCaso');

  // ==========================================
  // 3. API
  // ==========================================
  // ==========================================
  // 3. API & LOGIC
  // ==========================================
  let casosGestion = []; // Store all cases for filtering
  let usuariosGestion = []; // Store all users for filtering

  async function cargarDatosDashboard() {
    try {
      console.log("⏳ Conectando al servidor...");
      const respuesta = await fetch('/api/admin/dashboard');

      if (!respuesta.ok) throw new Error('Error en la respuesta del servidor');

      const datos = await respuesta.json();

      if (datos.success) {
        // Guardar rol
        rolUsuario = datos.rol_usuario || 'invitado';
        console.log("Rol de usuario:", rolUsuario);

        // Hide/Show Bitacora Button
        const btnBitacora = document.getElementById('btnBitacora');
        if (btnBitacora) {
          btnBitacora.style.display = (rolUsuario === 'admin') ? 'flex' : 'none';
        }

        // Transformación
        casos = datos.lista_casos_activos.map(casoBD => ({
          id: `UPSLP-${casoBD.id_caso}`,
          titulo: casoBD.nombre || 'Sin título',
          descripcion: casoBD.descripcion || '',
          estado: (casoBD.estado || '').toLowerCase(),
          notas: casoBD.descripcion || '',
          encargado: '',
          inicioReporte: casoBD.fecha_creacion || '',
          inicioAsignacion: '',
          finalizacion: ''
        }));

        renderCasos();

        // Renderizar herramientas en sidebar derecho
        if (datos.lista_herramientas && Array.isArray(datos.lista_herramientas)) {
          const sidebarMenu = document.querySelector('#sidebarDerecha .sidebar-menu');
          if (sidebarMenu) {
            sidebarMenu.innerHTML = ''; // Limpiar contenido previo
            datos.lista_herramientas.forEach(tool => {
              const btn = document.createElement('button');
              btn.className = 'sidebar-item';
              btn.type = 'button';
              btn.textContent = tool.nombre;
              btn.addEventListener('click', () => {
                if (tool.enlace) window.open(tool.enlace, '_blank');
              });
              sidebarMenu.appendChild(btn);
            });
          }
        }
      }

    } catch (error) {
      console.error("❌ Error:", error);
      if (track) {
        track.innerHTML = '<div style="padding:20px; color:white;">No se pudo conectar al servidor.</div>';
      }
    }
  }

  async function cargarCasosGestion() {
    try {
      const respuesta = await fetch('/api/admin/casos');
      if (!respuesta.ok) throw new Error(`HTTP error! status: ${respuesta.status}`);
      const datos = await respuesta.json();
      if (datos.success) {
        casosGestion = datos.casos; // Save for filtering
        filtrarYRenderizarCasos();
      } else {
        console.error("Error en respuesta de casos:", datos);
        alert('Error al cargar casos: ' + (datos.message || 'Error desconocido'));
      }
    } catch (error) {
      console.error("Error cargando casos:", error);
      alert('Error de conexión al cargar casos: ' + error.message);
    }
  }

  async function cargarUsuariosGestion() {
    try {
      const respuesta = await fetch('/api/admin/usuarios');
      if (!respuesta.ok) throw new Error(`HTTP error! status: ${respuesta.status}`);
      const datos = await respuesta.json();
      if (datos.success) {
        usuariosGestion = datos.usuarios;
        filtrarYRenderizarUsuarios();
      } else {
        console.error("Error en respuesta de usuarios:", datos);
        alert('Error al cargar usuarios: ' + (datos.message || 'Error desconocido'));
      }
    } catch (error) {
      console.error("Error cargando usuarios:", error);
      alert('Error de conexión al cargar usuarios: ' + error.message);
    }
  }

  function filtrarYRenderizarCasos() {
    const textoBusqueda = document.getElementById('busquedaCasos')?.value.toLowerCase() || '';
    const estadoFiltro = document.getElementById('filtroEstado')?.value.toLowerCase() || '';
    const fechaFiltro = document.getElementById('filtroFecha')?.value || '';

    const casosFiltrados = casosGestion.filter(caso => {
      const cumpleBusqueda = (
        caso.nombre.toLowerCase().includes(textoBusqueda) ||
        `UPSLP-${caso.id_caso}`.toLowerCase().includes(textoBusqueda)
      );

      const cumpleEstado = estadoFiltro === '' || caso.estado.toLowerCase() === estadoFiltro;

      let cumpleFecha = true;
      if (fechaFiltro) {
        const fechaCaso = caso.fecha_creacion.split('T')[0];
        cumpleFecha = fechaCaso === fechaFiltro;
      }

      return cumpleBusqueda && cumpleEstado && cumpleFecha;
    });

    renderCasosTabla(casosFiltrados);
  }

  function filtrarYRenderizarUsuarios() {
    const textoBusqueda = document.getElementById('busquedaUsuarios')?.value.toLowerCase() || '';

    const usuariosFiltrados = usuariosGestion.filter(user => {
      const email = user.mail || user.email || '';
      return (
        user.nombre.toLowerCase().includes(textoBusqueda) ||
        email.toLowerCase().includes(textoBusqueda)
      );
    });

    renderUsuariosTabla(usuariosFiltrados);
  }

  function renderCasosTabla(listaCasos) {
    const tbody = document.getElementById('tablaGestionCasosBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    if (listaCasos.length === 0) {
      tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 1rem;">No se encontraron casos.</td></tr>';
      return;
    }

    listaCasos.forEach(caso => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
          <td style="padding: 1rem;">UPSLP-${caso.id_caso}</td>
          <td style="padding: 1rem;">
              <span class="badge-estado-modal ${claseEstado(caso.estado)}">${getTextoBonito(caso.estado)}</span>
          </td>
          <td style="padding: 1rem;">${caso.nombre}</td>
          <td style="padding: 1rem;">${new Date(caso.fecha_creacion).toLocaleDateString()}</td>
          <td style="padding: 1rem;">
              <button class="btn btn-mini btn-secundario btn-editar-caso" data-id="${caso.id_caso}" title="Editar">✏️</button>
              <button class="btn btn-mini btn-secundario btn-eliminar-caso" data-id="${caso.id_caso}" title="Eliminar" style="margin-left: 0.5rem; color: #ef4444; border-color: #ef4444;">🗑️</button>
          </td>
      `;
      tbody.appendChild(tr);
    });

    document.querySelectorAll('.btn-editar-caso').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const id = e.target.closest('button').dataset.id;
        abrirModalEditarCaso(id);
      });
    });
  }

  function renderUsuariosTabla(listaUsuarios) {
    const tbody = document.getElementById('tablaGestionUsuariosBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    if (listaUsuarios.length === 0) {
      tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 1rem;">No se encontraron usuarios.</td></tr>';
      return;
    }

    listaUsuarios.forEach(user => {
      const tr = document.createElement('tr');
      // Add padding via style or class if needed, or rely on CSS
      tr.style.height = "3.5rem"; // Adding height for spacing
      tr.innerHTML = `
              <td style="padding: 1rem;">${user.id_usuario}</td>
              <td style="padding: 1rem;">${user.nombre}</td>
              <td style="padding: 1rem;">${user.mail || user.email || ''}</td>
              <td style="padding: 1rem;">${user.rol}</td>
              <td style="padding: 1rem;">
                  <button class="btn btn-mini btn-secundario btn-editar-usuario" data-id="${user.id_usuario}" title="Editar">✏️</button>
                  <button class="btn btn-mini btn-secundario btn-eliminar-usuario" data-id="${user.id_usuario}" title="Eliminar" style="margin-left: 0.5rem; color: #ef4444; border-color: #ef4444;">🗑️</button>
              </td>
          `;
      tbody.appendChild(tr);
    });
  }

  // --- NUEVAS FUNCIONES PARA EDITAR/ELIMINAR USUARIOS ---

  function abrirModalEditarUsuario(id) {
    const user = usuariosGestion.find(u => u.id_usuario == id);
    if (!user) return;

    const modal = document.getElementById('modalEditarUsuario');
    if (!modal) return;

    // Populate fields
    document.getElementById('editUserId').value = user.id_usuario;
    document.getElementById('editNombreUsuario').value = user.nombre;
    document.getElementById('editEmailUsuario').value = user.mail || user.email || '';
    document.getElementById('editRolUsuario').value = user.rol;
    document.getElementById('editPasswordUsuario').value = ''; // Reset password field

    abrirModal(modal);
  }

  async function guardarCambiosUsuario() {
    const id = document.getElementById('editUserId').value;
    const nombre = document.getElementById('editNombreUsuario').value.trim();
    const email = document.getElementById('editEmailUsuario').value.trim();
    const rol = document.getElementById('editRolUsuario').value;
    const password = document.getElementById('editPasswordUsuario').value.trim();

    if (!nombre || !email || !rol) {
      alert('Por favor complete los campos obligatorios y asegúrese de no dejar espacios en blanco.');
      return;
    }

    const payload = { nombre, email, rol };
    if (password) payload.password = password;

    try {
      const response = await fetch(`/api/admin/usuarios/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
      });

      const data = await response.json();

      if (data.success) {
        alert('Usuario actualizado correctamente');
        document.getElementById('modalEditarUsuario').classList.remove('activo');
        cargarUsuariosGestion(); // Reload list
      } else {
        alert('Error al actualizar: ' + data.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error de conexión al actualizar usuario');
    }
  }

  async function eliminarUsuario(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) return;

    try {
      const response = await fetch(`/api/admin/usuarios/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      });

      const data = await response.json();

      if (data.success) {
        alert('Usuario eliminado correctamente');
        cargarUsuariosGestion();
      } else {
        alert('Error al eliminar: ' + data.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error de conexión al eliminar usuario');
    }
  }


  async function eliminarCaso(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este caso?')) return;

    try {
      const response = await fetch(`/api/admin/casos/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      });

      const data = await response.json();

      if (data.success) {
        alert('Caso eliminado correctamente');
        cargarCasosGestion();
      } else {
        alert('Error al eliminar: ' + data.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error de conexión al eliminar caso');
    }
  }

  // Event Listeners for Filters
  const inputBusqueda = document.getElementById('busquedaCasos');
  const selectEstado = document.getElementById('filtroEstado');
  const inputFecha = document.getElementById('filtroFecha');
  const inputBusquedaUsuarios = document.getElementById('busquedaUsuarios');

  if (inputBusqueda) inputBusqueda.addEventListener('input', filtrarYRenderizarCasos);
  if (selectEstado) selectEstado.addEventListener('change', filtrarYRenderizarCasos);
  if (inputFecha) inputFecha.addEventListener('change', filtrarYRenderizarCasos);
  if (inputBusquedaUsuarios) inputBusquedaUsuarios.addEventListener('input', filtrarYRenderizarUsuarios);

  // Tab Switching Logic
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      // Remove active class from all tabs
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('active');
        b.style.borderBottom = 'none';
        b.style.color = '#94a3b8';
      });

      // Add active class to clicked tab
      btn.classList.add('active');
      btn.style.borderBottom = '2px solid #3b82f6';
      btn.style.color = 'white';

      const tab = btn.dataset.tab;
      if (tab === 'casos') {
        document.getElementById('adminCasosView').style.display = 'block';
        document.getElementById('adminUsuariosView').style.display = 'none';
        cargarCasosGestion();
      } else {
        document.getElementById('adminCasosView').style.display = 'none';
        document.getElementById('adminUsuariosView').style.display = 'block';
        cargarUsuariosGestion();
      }
    });
  });


  async function cargarCapturistas() {
    try {
      const respuesta = await fetch('/api/admin/capturistas');
      const datos = await respuesta.json();
      if (datos.success) {
        const select = document.getElementById('nuevoEncargado');
        if (select) {
          select.innerHTML = '<option value="">Seleccionar capturista...</option>';
          datos.capturistas.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id_usuario;
            option.textContent = user.nombre;
            select.appendChild(option);
          });
        }
      }
    } catch (error) {
      console.error("Error cargando capturistas:", error);
    }
  }

  // Helper to format date for datetime-local input (YYYY-MM-DDTHH:MM)
  function getNowForInput() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    return now.toISOString().slice(0, 16);
  }

  async function cargarBitacora() {
    try {
      const respuesta = await fetch('/api/admin/bitacora');
      if (!respuesta.ok) throw new Error(`HTTP error! status: ${respuesta.status}`);
      const datos = await respuesta.json();

      const tbody = document.getElementById('tablaBitacoraBody');
      if (!tbody) return;
      tbody.innerHTML = '';

      if (datos.success && datos.logs.length > 0) {
        datos.logs.forEach(log => {
          const tr = document.createElement('tr');
          tr.style.height = "3.5rem";
          tr.innerHTML = `
              <td style="padding: 1rem; border: 1px solid #e2e8f0;">${new Date(log.fecha_hora).toLocaleString()}</td>
              <td style="padding: 1rem; border: 1px solid #e2e8f0;">${log.usuario ? log.usuario.nombre : 'Sistema'}</td>
              <td style="padding: 1rem; border: 1px solid #e2e8f0;">${log.tipo_accion}</td>
              <td style="padding: 1rem; border: 1px solid #e2e8f0;">${log.descripcion}</td>
          `;
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding: 1rem;">No hay actividad reciente.</td></tr>';
      }
    } catch (error) {
      console.error("Error cargando bitácora:", error);
      alert('Error al cargar la bitácora');
    }
  }

  async function crearNuevoCaso() {
    const titulo = document.getElementById('nuevoTitulo').value.trim();
    const estado = document.getElementById('nuevoEstado').value;
    const encargadoId = document.getElementById('nuevoEncargado').value;
    const notas = document.getElementById('nuevoNotas').value.trim();

    if (!titulo) {
      alert('El título es obligatorio y no puede estar vacío.');
      return;
    }

    // Auto-generate date for backend
    const inicioReporte = getNowForInput();

    // Get CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
      const respuesta = await fetch('/api/admin/casos', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          nombre: titulo,
          estado: estado,
          descripcion: notas,
          id_usuario: encargadoId,
          fecha_creacion: inicioReporte
        })
      });

      const datos = await respuesta.json();
      if (datos.success) {
        alert("Caso creado exitosamente");
        cerrarModal(document.getElementById('modalNuevoCaso'));
        cargarCasosGestion(); // Recargar tabla
        cargarDatosDashboard(); // Recargar dashboard
      } else {
        alert("Error al crear caso: " + datos.message);
      }
    } catch (error) {
      console.error("Error creando caso:", error);
      alert("Error de conexión");
    }
  }

  async function crearNuevoUsuario() {
    const nombre = document.getElementById('nuevoNombreUsuario').value.trim();
    const email = document.getElementById('nuevoCorreoUsuario').value.trim();
    const password = document.getElementById('nuevoPasswordUsuario').value.trim();
    const celular = document.getElementById('nuevoCelularUsuario').value.trim();
    const rol = document.getElementById('nuevoRolUsuario').value;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!nombre || !email || !password || !rol) {
      alert("Por favor complete los campos obligatorios (Nombre, Email, Password, Rol) y asegúrese de no dejar espacios en blanco.");
      return;
    }

    try {
      const respuesta = await fetch('/api/admin/usuarios', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          nombre: nombre,
          email: email,
          password: password,
          celular: celular,
          rol: rol
        })
      });

      const datos = await respuesta.json();

      if (datos.success) {
        alert("Usuario creado exitosamente");
        cerrarModal(document.getElementById('modalNuevoUsuario'));
        cargarUsuariosGestion(); // Recargar tabla si está abierta

        // Limpiar formulario
        document.getElementById('nuevoNombreUsuario').value = '';
        document.getElementById('nuevoCorreoUsuario').value = '';
        document.getElementById('nuevoPasswordUsuario').value = '';
        document.getElementById('nuevoCelularUsuario').value = '';
      } else {
        alert("Error al crear usuario: " + datos.message);
      }

    } catch (error) {
      console.error("Error:", error);
      alert("Error de conexión al crear usuario");
    }
  }

  function abrirModalNuevoCaso() {
    const modal = document.getElementById('modalNuevoCaso');
    if (modal) {
      abrirModal(modal);
      cargarCapturistas();
    }
  }

  let casoActualId = null; // To store the ID of the case being edited

  async function abrirModalEditarCaso(id) {
    const modal = document.getElementById('modalEditarCaso');
    if (!modal) return;

    // Find case in local data
    const caso = casosGestion.find(c => c.id_caso == id);
    if (!caso) return;

    casoActualId = id;

    // Populate fields
    document.getElementById('campoId').value = `UPSLP-${caso.id_caso}`;
    document.getElementById('campoTitulo').value = caso.nombre;
    document.getElementById('campoEstado').value = caso.estado; // Assuming value matches
    document.getElementById('campoNotas').value = caso.descripcion || '';
    document.getElementById('campoInicioReporte').value = caso.fecha_creacion ? new Date(caso.fecha_creacion).toLocaleString() : '';

    // Populate Encargado
    // Check if assignments exist and get the latest one
    let nombreEncargado = "Sin asignar";
    if (caso.asignaciones && caso.asignaciones.length > 0) {
      // Assuming the last assignment is the current one
      const ultimaAsignacion = caso.asignaciones[caso.asignaciones.length - 1];
      if (ultimaAsignacion.usuario) {
        nombreEncargado = ultimaAsignacion.usuario.nombre;
      }
    }
    document.getElementById('campoEncargado').value = nombreEncargado;

    // Show modal
    abrirModal(modal);
  }

  async function guardarCambiosCaso() {
    if (!casoActualId) return;

    const titulo = document.getElementById('campoTitulo').value.trim();
    const estado = document.getElementById('campoEstado').value;
    const notas = document.getElementById('campoNotas').value.trim();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!titulo) {
      alert('El título no puede estar vacío.');
      return;
    }

    try {
      const respuesta = await fetch(`/api/admin/casos/${casoActualId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          nombre: titulo,
          estado: estado,
          descripcion: notas
        })
      });

      const datos = await respuesta.json();
      if (datos.success) {
        alert("Caso actualizado exitosamente");
        cerrarModal(document.getElementById('modalEditarCaso'));
        cargarCasosGestion(); // Reload table
        cargarDatosDashboard(); // Reload dashboard
      } else {
        alert("Error al actualizar caso: " + datos.message);
      }
    } catch (error) {
      console.error("Error actualizando caso:", error);
      alert("Error de conexión");
    }
  }

  // Override click handlers
  setTimeout(() => {
    const btnNewCase = document.getElementById('btnNewCase');
    const btnNuevoCasoDesdeGestion = document.getElementById('btnNuevoCasoDesdeGestion');
    const btnGuardarCaso = document.getElementById('btnGuardarCaso');

    // Main panel button opens Create Modal directly
    if (btnNewCase) btnNewCase.onclick = abrirModalNuevoCaso;

    // Button inside Manage Modal also opens Create Modal
    if (btnNuevoCasoDesdeGestion) btnNuevoCasoDesdeGestion.onclick = abrirModalNuevoCaso;

    // Save button in Edit Modal
    if (btnGuardarCaso) btnGuardarCaso.onclick = guardarCambiosCaso;
  }, 500);


  // ==========================================
  // 4. HELPERS
  // ==========================================
  function normalizarEstado(e) {
    if (!e) return '';
    const lower = e.toLowerCase().trim();
    if (lower === 'active' || lower === 'activo' || lower === 'activa') return 'active';
    if (lower === 'in progress' || lower === 'in_progress' || lower === 'en progreso' || lower === 'en_progreso') return 'in_progress';
    if (lower === 'paused' || lower === 'pausado') return 'paused';
    if (lower === 'closed' || lower === 'cerrado' || lower === 'finalizado') return 'closed';
    return 'closed'; // Default
  }

  function claseEstado(estado) {
    const e = normalizarEstado(estado);
    switch (e) {
      case 'active': return 'estado-activo';
      case 'in_progress': return 'estado-progreso';
      case 'paused': return 'estado-pausado';
      case 'closed': return 'estado-finalizado';
      default: return 'estado-finalizado';
    }
  }

  function getTextoBonito(estado) {
    const e = normalizarEstado(estado);
    switch (e) {
      case 'active': return 'Activo';
      case 'in_progress': return 'En Progreso';
      case 'paused': return 'Pausado';
      case 'closed': return 'Cerrado';
      default: return 'Cerrado';
    }
  }

  function buscarCasoPorId(id) {
    return casos.find(c => c.id === id);
  }

  // ==========================================
  // 5. RENDER
  // ==========================================
  function crearTarjetaCaso(caso) {
    const article = document.createElement('article');
    article.className = 'caso-card';
    article.dataset.caseId = caso.id;

    const claseColor = claseEstado(caso.estado);
    const textoEstado = getTextoBonito(caso.estado);

    // Mostrar Título y Descripción truncada
    const descripcionCorta = caso.descripcion.length > 100
      ? caso.descripcion.substring(0, 100) + '...'
      : caso.descripcion;

    article.innerHTML = `
      <div class="caso-header">
        <span class="estado-badge ${claseColor}">
          <span class="estado-icono"></span>
          ${textoEstado}
        </span>
      </div>
      <div class="caso-body" style="display: flex; flex-direction: column;">
        <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; color: #ffffff;">${caso.titulo}</h4>
        <p style="font-size: 0.85rem; color: #e0e0e0;">${descripcionCorta}</p>
      </div>
      <div class="caso-footer">
        <span>ID:</span>${caso.id}
      </div>
    `;

    // SIN INTERACCIÓN DE CLICK (SOLO VISUALIZACIÓN)
    // article.addEventListener('click', () => abrirSlideOver(caso.id));

    return article;
  }

  function renderCasos() {
    if (!track) return;
    track.innerHTML = '';

    if (casos.length === 0) {
      track.innerHTML = '<div style="padding:20px; color:white;">No hay casos activos.</div>';
      return;
    }

    casos.forEach(caso => track.appendChild(crearTarjetaCaso(caso)));
  }

  // ==========================================
  // 6. INTERACCIÓN
  // ==========================================
  if (slider && btnPrev && btnNext) {
    let isDragging = false;
    let startX, scrollLeft;

    slider.addEventListener('mousedown', (e) => {
      isDragging = true;
      slider.classList.add('arrastrando');
      startX = e.pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('mouseup', () => {
      isDragging = false;
      slider.classList.remove('arrastrando');
    });

    slider.addEventListener('mouseleave', () => {
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

    btnPrev.addEventListener('click', () => slider.scrollBy({ left: -280, behavior: 'smooth' }));
    btnNext.addEventListener('click', () => slider.scrollBy({ left: 280, behavior: 'smooth' }));
  }

  function abrirModal(modal) { if (modal) modal.classList.add('activo'); }
  function cerrarModal(modal) { if (modal) modal.classList.remove('activo'); }

  // ==========================================
  // EVENT DELEGATION FOR DYNAMIC CONTENT
  // ==========================================
  document.addEventListener('click', (e) => {
    const target = e.target;

    // Button: New Case (Direct Access)
    if (target.closest('#btnNewCase')) {
      console.log("🖱️ Click en New Case (Directo)");
      const modal = document.getElementById('modalNuevoCaso');
      if (modal) {
        abrirModal(modal);
        cargarCapturistas();
      }
    }

    // Button: Admin (Opens Management Modal)
    if (target.closest('#btnAdmin')) {
      const modal = document.getElementById('modalGestionCasos');
      if (modal) {
        abrirModal(modal);
        cargarCasosGestion();
        // Default to cases tab
        document.querySelector('.tab-btn[data-tab="casos"]')?.click();
      }
    }

    // Button: Create Case (Submit)
    if (target.closest('#btnCrearCaso')) {
      crearNuevoCaso();
    }

    // Button: Create User (Submit)
    if (target.closest('#btnCrearUsuario')) {
      crearNuevoUsuario();
    }

    // Button: Edit User (Submit)
    if (target.closest('#btnGuardarUsuario')) {
      guardarCambiosUsuario();
    }

    // Button: Edit User (Open Modal)
    if (target.closest('.btn-editar-usuario')) {
      const id = target.closest('.btn-editar-usuario').dataset.id;
      abrirModalEditarUsuario(id);
    }

    // Button: Delete User
    if (target.closest('.btn-eliminar-usuario')) {
      const id = target.closest('.btn-eliminar-usuario').dataset.id;
      eliminarUsuario(id);
    }

    // Button: Bitacora
    if (target.closest('#btnBitacora')) {
      const modal = document.getElementById('modalBitacora');
      if (modal) {
        abrirModal(modal);
        cargarBitacora();
      }
    }

    // Close Modal Button (Generic for all modals including Bitacora)
    if (target.closest('.modal-close')) {
      const modal = target.closest('.modal-overlay');
      cerrarModal(modal);
    }

    // Button: Delete Case
    if (target.closest('.btn-eliminar-caso')) {
      const id = target.closest('.btn-eliminar-caso').dataset.id;
      eliminarCaso(id);
    }

    // Other menu buttons
    if (target.closest('#btnNewUser')) abrirModal(document.getElementById('modalNuevoUsuario'));
    if (target.closest('#btnEvidence')) abrirModal(document.getElementById('modalEvidencias'));
    if (target.closest('#btnReport')) abrirModal(document.getElementById('modalReporte'));

    // Close modals
    if (target.hasAttribute('data-modal-close')) {
      const overlay = target.closest('.modal-overlay');
      if (overlay) overlay.classList.remove('activo');
    }

    if (target.classList.contains('modal-overlay')) {
      target.classList.remove('activo');
    }
  });

  cargarDatosDashboard();

});
