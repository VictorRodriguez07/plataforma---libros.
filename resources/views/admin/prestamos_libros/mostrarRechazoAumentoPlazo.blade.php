@extends('adminlte::page')

@section('title', 'Confirmar rechazo del préstamo')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background: #f8f9fa;">
    <div class="card shadow-lg rounded-4 p-4" style="max-width: 480px; width: 100%; border: none;">
        <div class="card-body text-center">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto" width="64" height="64" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                    <circle cx="32" cy="32" r="30" stroke-opacity="0.2"/>
                    <line x1="20" y1="20" x2="44" y2="44" />
                    <line x1="44" y1="20" x2="20" y2="44" />
                </svg>
            </div>

            <h3 class="mb-3 fw-bold text-danger">¿Confirmar rechazo solicitud aumento de plazo?</h3>
            <p class="card-text text-secondary mb-4 fs-6">
                Esta acción rechazará la solicitud de aumento de plazo.
            </p>

            <form method="POST" action="{{ route('admin.prestamos.rechazoAumentoPlazoPrestamoLibro', $id) }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-lg w-100 mb-3 shadow-sm" style="font-weight: 600; letter-spacing: 0.03em;">
                    Confirmar rechazo
                </button>
            </form>

            <a href="{{ route('admin.prestamos.listar') }}" class="btn btn-outline-secondary btn-lg w-100" style="font-weight: 600;">
                Cancelar
            </a>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ mix('js/app.js') }}"></script>
@endsection
