/**
 * Lógica para la vista global de evidencias
 */

let todasEvidencias = [];
let casosAsignados = [];

console.log('Script evidencias-global.js cargado');

document.addEventListener('DOMContentLoaded', async () => {
    console.log('DOM Content Loaded - Iniciando carga de datos');
    try {
        await cargarDatos();
    } catch (e) {
        console.error('Error fatal en carga de datos:', e);
    } finally {
        console.log('Configurando filtros...');
        setupFilters();
    }
});

/**
 * Cargar datos iniciales
 */
async function cargarDatos() {
    try {
        console.log('Iniciando cargarDatos...');
        loading.show('Cargando evidencias...');

        // Cargar evidencias y casos en paralelo
        const [evidenciasResponse, casosResponse] = await Promise.all([
            capturistaAPI.getAllEvidencias(),
            capturistaAPI.getCasosAsignados()
        ]);

        console.log('Respuestas API:', { evidenciasResponse, casosResponse });

        if (evidenciasResponse.success) {
            todasEvidencias = evidenciasResponse.data;
            console.log('Evidencias cargadas:', todasEvidencias.length);
            renderEvidencias(todasEvidencias);
        } else {
            console.error('Error en respuesta de evidencias:', evidenciasResponse);
            toast.error('Error al cargar evidencias');
        }

        if (casosResponse.success) {
            casosAsignados = casosResponse.data;
            console.log('Casos asignados cargados:', casosAsignados.length);
        } else {
            console.error('Error en respuesta de casos:', casosResponse);
        }

        loading.hide();
    } catch (error) {
        loading.hide();
        console.error('Error en cargarDatos:', error);
        toast.error('Error al cargar datos: ' + error.message);
    }
}

/**
 * Renderizar lista de evidencias
 */
function renderEvidencias(evidencias) {
    const container = document.getElementById('evidenciasList');

    if (evidencias.length === 0) {
        container.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M10 2a6 6 0 0 1 4.8 9.6l4.8 4.8-1.4 1.4-4.8-4.8A6 6 0 1 1 10 2Zm0 2a4 4 0 1 0 4 4 4.005 4.005 0 0 0-4-4Z" />
                    </svg>
                </div>
                <h3 class="empty-state-title">No hay evidencias encontradas</h3>
                <p class="empty-state-description">Intenta ajustar los filtros o agrega una nueva evidencia.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = evidencias.map(evidencia => `
        <div class="evidencia-card">
            <div class="evidencia-header">
                <span class="evidencia-tipo">${evidencia.tipo}</span>
                <div class="evidencia-actions">
                    <button class="btn-icon-only" onclick="editarEvidencia(${evidencia.id_evidencia})" title="Editar">
                        <svg viewBox="0 0 24 24" width="18" height="18">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                    </button>
                    <button class="btn-icon-only delete" onclick="eliminarEvidencia(${evidencia.id_evidencia})" title="Eliminar">
                        <svg viewBox="0 0 24 24" width="18" height="18">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <a href="/capturista/casos/${evidencia.caso.id_caso}" class="evidencia-caso">
                Caso: ${evidencia.caso.nombre} (${evidencia.caso.codigo})
            </a>
            <p class="evidencia-descripcion">${evidencia.descripcion}</p>
            <div class="evidencia-meta">
                <span>${new Date(evidencia.fecha_creacion).toLocaleDateString()}</span>
                <span>ID: ${evidencia.id_evidencia}</span>
            </div>
        </div>
    `).join('');
}

/**
 * Configurar filtros
 */
function setupFilters() {
    const searchInput = document.getElementById('searchEvidencia');
    const typeSelect = document.getElementById('filterTipo');

    const filterFn = () => {
        const searchTerm = searchInput.value.toLowerCase();
        const typeFilter = typeSelect.value;

        const filtered = todasEvidencias.filter(ev => {
            const matchesSearch =
                ev.descripcion.toLowerCase().includes(searchTerm) ||
                ev.tipo.toLowerCase().includes(searchTerm) ||
                ev.caso.nombre.toLowerCase().includes(searchTerm);

            // Filtro flexible: si no hay filtro seleccionado, mostrar todo
            // Si hay filtro, buscar coincidencia parcial case-insensitive
            const matchesType = !typeFilter || ev.tipo.toLowerCase().includes(typeFilter.toLowerCase());

            return matchesSearch && matchesType;
        });

        renderEvidencias(filtered);
    };

    searchInput.addEventListener('input', filterFn);
    typeSelect.addEventListener('change', filterFn);
}

/**
 * Abrir modal para nueva evidencia
 */
function abrirModalNuevaEvidencia() {
    console.log('abrirModalNuevaEvidencia llamado');
    // Generar opciones de casos
    const casosOptions = casosAsignados.map(caso =>
        `<option value="${caso.id_caso}">${caso.nombre} (${caso.codigo_caso || 'N/A'})</option>`
    ).join('');

    const formHTML = `
        <form id="formNuevaEvidencia">
            <div class="form-group">
                <label class="form-label form-label-required">Caso</label>
                <select class="form-select" id="evidenciaCaso" required>
                    <option value="">Seleccione un caso...</option>
                    ${casosOptions}
                </select>
            </div>
            <div class="form-group">
                <label class="form-label form-label-required">Tipo de Evidencia</label>
                <select class="form-select" id="evidenciaTipo" required>
                    <option value="Imagen">Imagen</option>
                    <option value="Documento">Documento</option>
                    <option value="Enlace">Enlace</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label form-label-required">Descripción</label>
                <textarea class="form-textarea" id="evidenciaDescripcion" required rows="4" placeholder="Describe la evidencia encontrada..."></textarea>
            </div>
        </form>
    `;

    console.log('HTML del formulario generado, llamando a modal.show...');
    try {
        modal.show({
            title: 'Nueva Evidencia',
            content: formHTML,
            buttons: [
                {
                    text: 'Cancelar',
                    class: 'modal-btn-secondary'
                },
                {
                    text: 'Guardar',
                    class: 'modal-btn-primary',
                    onClick: async () => {
                        const idCaso = document.getElementById('evidenciaCaso').value;
                        const tipo = document.getElementById('evidenciaTipo').value;
                        const descripcion = document.getElementById('evidenciaDescripcion').value;

                        if (!idCaso || !tipo || !descripcion) {
                            toast.error('Todos los campos son obligatorios');
                            return;
                        }

                        try {
                            loading.show('Guardando evidencia...');
                            const response = await capturistaAPI.agregarEvidencia({
                                id_caso: idCaso,
                                tipo,
                                descripcion
                            });
                            loading.hide();

                            if (response.success) {
                                toast.success('Evidencia agregada exitosamente');
                                modal.close();
                                cargarDatos(); // Recargar lista
                            }
                        } catch (error) {
                            loading.hide();
                            toast.error('Error al guardar: ' + error.message);
                        }
                    },
                    closeOnClick: false
                }
            ]
        });
    } catch (error) {
        console.error('Error al abrir modal:', error);
        toast.error('Error al abrir el formulario: ' + error.message);
    }

}

/**
 * Editar evidencia
 */
function editarEvidencia(id) {
    const evidencia = todasEvidencias.find(e => e.id_evidencia === id);
    if (!evidencia) return;

    const formHTML = `
        <form id="formEditarEvidencia">
            <div class="form-group">
                <label class="form-label">Caso</label>
                <input type="text" class="form-input" value="${evidencia.caso.nombre}" disabled>
                <p class="form-help">El caso no se puede cambiar.</p>
            </div>
            <div class="form-group">
                <label class="form-label form-label-required">Tipo de Evidencia</label>
                <select class="form-select" id="evidenciaTipo" required>
                    <option value="Imagen" ${evidencia.tipo === 'Imagen' ? 'selected' : ''}>Imagen</option>
                    <option value="Documento" ${evidencia.tipo === 'Documento' ? 'selected' : ''}>Documento</option>
                    <option value="Enlace" ${evidencia.tipo === 'Enlace' ? 'selected' : ''}>Enlace</option>
                    <option value="Otro" ${evidencia.tipo === 'Otro' ? 'selected' : ''}>Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label form-label-required">Descripción</label>
                <textarea class="form-textarea" id="evidenciaDescripcion" required rows="4">${evidencia.descripcion}</textarea>
            </div>
        </form>
    `;

    modal.show({
        title: 'Editar Evidencia',
        content: formHTML,
        buttons: [
            {
                text: 'Cancelar',
                class: 'modal-btn-secondary'
            },
            {
                text: 'Actualizar',
                class: 'modal-btn-primary',
                onClick: async () => {
                    const tipo = document.getElementById('evidenciaTipo').value;
                    const descripcion = document.getElementById('evidenciaDescripcion').value;

                    if (!tipo || !descripcion) {
                        toast.error('Todos los campos son obligatorios');
                        return;
                    }

                    try {
                        loading.show('Actualizando evidencia...');
                        const response = await capturistaAPI.actualizarEvidencia(id, {
                            tipo,
                            descripcion
                        });
                        loading.hide();

                        if (response.success) {
                            toast.success('Evidencia actualizada exitosamente');
                            modal.close();
                            cargarDatos();
                        }
                    } catch (error) {
                        loading.hide();
                        toast.error('Error al actualizar: ' + error.message);
                    }
                },
                closeOnClick: false
            }
        ]
    });
}

/**
 * Eliminar evidencia
 */
function eliminarEvidencia(id) {
    modal.confirm(
        '¿Estás seguro de que deseas eliminar esta evidencia? Esta acción no se puede deshacer.',
        async () => {
            try {
                loading.show('Eliminando evidencia...');
                const response = await capturistaAPI.eliminarEvidencia(id);
                loading.hide();

                if (response.success) {
                    toast.success('Evidencia eliminada exitosamente');
                    modal.close();
                    cargarDatos();
                }
            } catch (error) {
                loading.hide();
                toast.error('Error al eliminar: ' + error.message);
            }
        }
    );
}
// Exponer funciones al scope global
window.abrirModalNuevaEvidencia = abrirModalNuevaEvidencia;
window.editarEvidencia = editarEvidencia;
window.eliminarEvidencia = eliminarEvidencia;
window.renderEvidencias = renderEvidencias;
window.setupFilters = setupFilters;
window.cargarDatos = cargarDatos;
