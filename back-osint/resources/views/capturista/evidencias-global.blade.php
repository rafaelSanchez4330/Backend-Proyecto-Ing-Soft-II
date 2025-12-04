@extends('layouts.dashboard')

@section('content')
<div class="capturista-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Gestión de Evidencias</h1>
            <p class="page-subtitle">Visualiza y gestiona todas las evidencias de tus casos asignados.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="abrirModalNuevaEvidencia()">
                <span class="btn-icon">+</span>
                Nueva Evidencia
            </button>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="filters-bar">
        <div class="search-box">
            <span class="search-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M10 2a8 8 0 1 1-5.3 14l-3.1 3.1-1.4-1.4 3.1-3.1A8 8 0 0 1 10 2Zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12Z" />
                </svg>
            </span>
            <input type="text" id="searchEvidencia" placeholder="Buscar por descripción, tipo o caso..." class="form-input">
        </div>
        <div class="filter-group">
            <select id="filterTipo" class="form-select">
                <option value="">Todos los tipos</option>
                <option value="Imagen">Imagen</option>
                <option value="Documento">Documento</option>
                <option value="Enlace">Enlace</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
    </div>

    <!-- Lista de Evidencias -->
    <div class="evidencias-grid" id="evidenciasList">
        <!-- Se llenará dinámicamente con JS -->
        <div class="loading-state">
            <div class="spinner"></div>
            <p>Cargando evidencias...</p>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/capturista/capturista.css') }}">
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .filters-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        background: white;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        align-items: center;
    }

    .search-box {
        position: relative;
        flex: 1;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .search-icon svg {
        width: 20px;
        height: 20px;
    }

    .search-box .form-input {
        padding-left: 2.5rem;
        width: 100%;
    }

    .filter-group {
        min-width: 200px;
    }

    .evidencias-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .evidencia-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
    }
    
    .evidencia-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .evidencia-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .evidencia-tipo {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--bg-secondary);
        color: var(--text-secondary);
    }
    
    .evidencia-caso {
        font-size: 0.875rem;
        color: var(--primary-color);
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
        text-decoration: none;
    }
    
    .evidencia-descripcion {
        color: var(--text-primary);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    
    .evidencia-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--text-secondary);
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
        margin-top: auto;
    }

    .evidencia-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon-only {
        padding: 0.25rem;
        border-radius: 4px;
        color: var(--text-secondary);
        transition: all 0.2s;
        background: none;
        border: none;
        cursor: pointer;
    }

    .btn-icon-only:hover {
        background: var(--bg-secondary);
        color: var(--primary-color);
    }
    
    .btn-icon-only.delete:hover {
        color: var(--danger-color);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/dashboard/services/capturista-api.js') }}"></script>
<script src="{{ asset('assets/dashboard/components/modal.js') }}"></script>
<script src="{{ asset('assets/dashboard/components/toast.js') }}"></script>
<script src="{{ asset('assets/dashboard/components/loading.js') }}"></script>
<script src="{{ asset('assets/dashboard/capturista/evidencias-global.js') }}"></script>
@endpush
@endsection
