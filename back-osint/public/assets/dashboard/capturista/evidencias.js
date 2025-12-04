/**
 * Lógica para gestión de evidencias
 */

/**
 * Agregar nueva evidencia
 */
function agregarEvidencia() {
    const formHTML = `
    <form id="formEvidencia">
      <div class="form-group">
        <label class="form-label form-label-required">Tipo de Evidencia</label>
        <select class="form-select" id="evidenciaTipo" required>
          <option value="">Seleccionar tipo...</option>
          <option value="Captura de Pantalla">Captura de Pantalla</option>
          <option value="Documento">Documento</option>
          <option value="Registro DNS">Registro DNS</option>
          <option value="Perfil de Redes Sociales">Perfil de Redes Sociales</option>
          <option value="Análisis de Dominio">Análisis de Dominio</option>
          <option value="Conversación">Conversación</option>
          <option value="Otro">Otro</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label form-label-required">Descripción</label>
        <textarea class="form-textarea" id="evidenciaDescripcion" required placeholder="Describe la evidencia en detalle..."></textarea>
        <p class="form-help">Incluye todos los detalles relevantes como fechas, URLs, nombres de usuario, etc.</p>
      </div>
    </form>
  `;

    modal.show({
        title: 'Agregar Nueva Evidencia',
        content: formHTML,
        size: 'medium',
        buttons: [
            {
                text: 'Cancelar',
                class: 'modal-btn-secondary',
                onClick: () => { }
            },
            {
                text: 'Guardar Evidencia',
                class: 'modal-btn-primary',
                onClick: async () => {
                    const tipo = document.getElementById('evidenciaTipo').value;
                    const descripcion = document.getElementById('evidenciaDescripcion').value;

                    if (!tipo || !descripcion) {
                        toast.error('Por favor completa todos los campos requeridos');
                        return;
                    }

                    try {
                        loading.show('Guardando evidencia...');

                        const response = await capturistaAPI.agregarEvidencia({
                            id_caso: window.casoData.id_caso,
                            tipo: tipo,
                            descripcion: descripcion
                        });

                        loading.hide();

                        if (response.success) {
                            toast.success('Evidencia agregada exitosamente');
                            // Recargar la página para mostrar la nueva evidencia
                            setTimeout(() => location.reload(), 1000);
                        }
                    } catch (error) {
                        loading.hide();
                        toast.error('Error al agregar evidencia: ' + error.message);
                    }
                },
                closeOnClick: false
            }
        ]
    });
}

/**
 * Editar evidencia existente
 */
async function editarEvidencia(idEvidencia) {
    try {
        loading.show('Cargando evidencia...');

        // Obtener datos actuales de la evidencia
        const evidenciaElement = document.querySelector(`[data-evidencia-id="${idEvidencia}"]`);
        const tipoActual = evidenciaElement.querySelector('.evidencia-tipo').textContent.trim();
        const descripcionActual = evidenciaElement.querySelector('.evidencia-descripcion').textContent.trim();

        loading.hide();

        const formHTML = `
      <form id="formEditarEvidencia">
        <div class="form-group">
          <label class="form-label form-label-required">Tipo de Evidencia</label>
          <select class="form-select" id="editEvidenciaTipo" required>
            <option value="Captura de Pantalla" ${tipoActual === 'Captura de Pantalla' ? 'selected' : ''}>Captura de Pantalla</option>
            <option value="Documento" ${tipoActual === 'Documento' ? 'selected' : ''}>Documento</option>
            <option value="Registro DNS" ${tipoActual === 'Registro DNS' ? 'selected' : ''}>Registro DNS</option>
            <option value="Perfil de Redes Sociales" ${tipoActual === 'Perfil de Redes Sociales' ? 'selected' : ''}>Perfil de Redes Sociales</option>
            <option value="Análisis de Dominio" ${tipoActual === 'Análisis de Dominio' ? 'selected' : ''}>Análisis de Dominio</option>
            <option value="Conversación" ${tipoActual === 'Conversación' ? 'selected' : ''}>Conversación</option>
            <option value="Otro" ${tipoActual === 'Otro' ? 'selected' : ''}>Otro</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label form-label-required">Descripción</label>
          <textarea class="form-textarea" id="editEvidenciaDescripcion" required>${descripcionActual}</textarea>
        </div>
      </form>
    `;

        modal.show({
            title: 'Editar Evidencia',
            content: formHTML,
            size: 'medium',
            buttons: [
                {
                    text: 'Cancelar',
                    class: 'modal-btn-secondary',
                    onClick: () => { }
                },
                {
                    text: 'Guardar Cambios',
                    class: 'modal-btn-primary',
                    onClick: async () => {
                        const tipo = document.getElementById('editEvidenciaTipo').value;
                        const descripcion = document.getElementById('editEvidenciaDescripcion').value;

                        if (!tipo || !descripcion) {
                            toast.error('Por favor completa todos los campos requeridos');
                            return;
                        }

                        try {
                            loading.show('Actualizando evidencia...');

                            const response = await capturistaAPI.actualizarEvidencia(idEvidencia, {
                                tipo: tipo,
                                descripcion: descripcion
                            });

                            loading.hide();

                            if (response.success) {
                                toast.success('Evidencia actualizada exitosamente');
                                setTimeout(() => location.reload(), 1000);
                            }
                        } catch (error) {
                            loading.hide();
                            toast.error('Error al actualizar evidencia: ' + error.message);
                        }
                    },
                    closeOnClick: false
                }
            ]
        });
    } catch (error) {
        loading.hide();
        toast.error('Error al cargar evidencia: ' + error.message);
    }
}

/**
 * Eliminar evidencia
 */
function eliminarEvidencia(idEvidencia) {
    modal.confirm(
        '¿Estás seguro de que deseas eliminar esta evidencia? Esta acción no se puede deshacer.',
        async () => {
            try {
                loading.show('Eliminando evidencia...');

                const response = await capturistaAPI.eliminarEvidencia(idEvidencia);

                loading.hide();

                if (response.success) {
                    toast.success('Evidencia eliminada exitosamente');
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (error) {
                loading.hide();
                toast.error('Error al eliminar evidencia: ' + error.message);
            }
        }
    );
}

// Event listener para botón de agregar evidencia
document.addEventListener('DOMContentLoaded', function () {
    const btnAgregar = document.getElementById('btnAgregarEvidencia');
    if (btnAgregar) {
        btnAgregar.addEventListener('click', agregarEvidencia);
    }
});
