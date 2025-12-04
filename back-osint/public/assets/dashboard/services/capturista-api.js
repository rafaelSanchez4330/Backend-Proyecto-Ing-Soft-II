/**
 * Servicio API para el Módulo Capturista
 * Maneja todas las llamadas a los endpoints del backend
 */

class CapturistaAPI {
    constructor() {
        // La URL base ahora apunta a las rutas definidas en web.php
        this.baseURL = '/capturista/api';
        this.tokenKey = 'auth_token';
    }

    /**
     * Obtener token de autenticación del localStorage
     */
    getToken() {
        return localStorage.getItem('auth_token') || '';
    }

    /**
     * Método genérico para realizar peticiones
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;

        // Configurar headers por defecto
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };

        // Obtener token CSRF del meta tag (necesario para auth por sesión)
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }

        // Intentar obtener token Bearer (por si acaso se usa en el futuro)
        const token = this.getToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const config = {
            ...options,
            headers: {
                ...headers,
                ...options.headers
            },
            // Importante: incluir cookies de sesión
            credentials: 'include'
        };

        try {
            const response = await fetch(url, config);
            return await this.handleResponse(response);
        } catch (error) {
            console.error(`Error en la petición a ${url}:`, error);
            throw error;
        }
    }

    /**
     * Configurar headers para las peticiones
     */
    getHeaders() {
        return {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.token}`,
            'Accept': 'application/json'
        };
    }

    /**
     * Manejo centralizado de errores
     */
    async handleResponse(response) {
        if (!response.ok) {
            const error = await response.json().catch(() => ({
                message: 'Error en la comunicación con el servidor'
            }));
            throw new Error(error.message || `HTTP error! status: ${response.status}`);
        }
        return await response.json();
    }

    /**
     * Obtener todos los casos asignados al usuario
     */
    async getCasosAsignados() {
        try {
            return await this.request('/casos', {
                method: 'GET'
            });
        } catch (error) {
            console.error('Error al obtener casos:', error);
            throw error;
        }
    }

    /**
     * Ver detalles de un caso específico
     * @param {number} id - ID del caso
     */
    async verCaso(id) {
        try {
            return await this.request(`/casos/${id}`, {
                method: 'GET'
            });
        } catch (error) {
            console.error(`Error al obtener caso ${id}:`, error);
            throw error;
        }
    }

    /**
     * Obtener todas las evidencias de los casos asignados
     */
    async getAllEvidencias() {
        try {
            return await this.request('/evidencias', {
                method: 'GET'
            });
        } catch (error) {
            console.error('Error al obtener todas las evidencias:', error);
            throw error;
        }
    }

    /**
     * Obtener evidencias de un caso
     * @param {number} idCaso - ID del caso
     */
    async getEvidencias(idCaso) {
        try {
            return await this.request(`/casos/${idCaso}/evidencias`, {
                method: 'GET'
            });
        } catch (error) {
            console.error(`Error al obtener evidencias del caso ${idCaso}:`, error);
            throw error;
        }
    }

    /**
     * Agregar nueva evidencia
     * @param {Object} data - Datos de la evidencia {id_caso, tipo, descripcion}
     */
    async agregarEvidencia(data) {
        try {
            return await this.request('/evidencias', {
                method: 'POST',
                body: JSON.stringify(data)
            });
        } catch (error) {
            console.error('Error al agregar evidencia:', error);
            throw error;
        }
    }

    /**
     * Actualizar evidencia existente
     * @param {number} id - ID de la evidencia
     * @param {Object} data - Datos actualizados {tipo, descripcion}
     */
    async actualizarEvidencia(id, data) {
        try {
            return await this.request(`/evidencias/${id}`, {
                method: 'PUT',
                body: JSON.stringify(data)
            });
        } catch (error) {
            console.error(`Error al actualizar evidencia ${id}:`, error);
            throw error;
        }
    }

    /**
     * Eliminar evidencia
     * @param {number} id - ID de la evidencia
     */
    async eliminarEvidencia(id) {
        try {
            return await this.request(`/evidencias/${id}`, {
                method: 'DELETE'
            });
        } catch (error) {
            console.error(`Error al eliminar evidencia ${id}:`, error);
            throw error;
        }
    }

    /**
     * Generar reporte completo del caso
     * @param {number} idCaso - ID del caso
     */
    async generarReporteCompleto(idCaso) {
        try {
            return await this.request(`/casos/${idCaso}/reporte-completo`, {
                method: 'GET'
            });
        } catch (error) {
            console.error(`Error al generar reporte completo del caso ${idCaso}:`, error);
            throw error;
        }
    }

    /**
     * Generar reporte de evidencias
     * @param {number} idCaso - ID del caso
     */
    async generarReporteEvidencias(idCaso) {
        try {
            return await this.request(`/casos/${idCaso}/reporte-evidencias`, {
                method: 'GET'
            });
        } catch (error) {
            console.error(`Error al generar reporte de evidencias del caso ${idCaso}:`, error);
            throw error;
        }
    }

    /**
     * Generar reporte personalizado
     * @param {number} idCaso - ID del caso
     * @param {Object} data - Datos del reporte {tipo_reporte, datos}
     */
    async generarReportePersonalizado(idCaso, data) {
        try {
            return await this.request(`/casos/${idCaso}/reporte-personalizado`, {
                method: 'POST',
                body: JSON.stringify(data)
            });
        } catch (error) {
            console.error(`Error al generar reporte personalizado del caso ${idCaso}:`, error);
            throw error;
        }
    }

    /**
     * Listar reportes generados de un caso
     * @param {number} idCaso - ID del caso
     */
    async listarReportes(idCaso) {
        try {
            return await this.request(`/casos/${idCaso}/reportes`, {
                method: 'GET'
            });
        } catch (error) {
            console.error(`Error al listar reportes del caso ${idCaso}:`, error);
            throw error;
        }
    }

    /**
     * Descargar reporte
     * @param {string} nombreArchivo - Nombre del archivo de reporte
     */
    async descargarReporte(nombreArchivo) {
        try {
            const response = await fetch(`${this.baseURL}/reportes/${nombreArchivo}/descargar`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Obtener el blob del archivo
            const blob = await response.blob();

            // Crear URL temporal para descarga
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = nombreArchivo;
            document.body.appendChild(a);
            a.click();

            // Limpiar
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            return { success: true, message: 'Reporte descargado exitosamente' };
        } catch (error) {
            console.error(`Error al descargar reporte ${nombreArchivo}:`, error);
            throw error;
        }
    }
}

// Exportar instancia única del servicio
const capturistaAPI = new CapturistaAPI();
