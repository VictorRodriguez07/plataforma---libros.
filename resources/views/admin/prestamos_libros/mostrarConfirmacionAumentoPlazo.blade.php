@extends('adminlte::page')

@section('title', 'Confirmar aprobación del préstamo')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background: #f8f9fa;">
    <div class="card shadow-lg rounded-4 p-4" style="max-width: 480px; width: 100%; border: none;">
        <div class="card-body text-center">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto" width="64" height="64" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                    <circle cx="32" cy="32" r="30" stroke-opacity="0.2"/>
                    <path d="M20 33l10 10 20-20" />
                </svg>
            </div>

            <h3 class="mb-3 fw-bold text-success">¿Confirmar aprobación del aumento de plazo de entrega del préstamo?</h3>
            <p class="card-text text-secondary mb-4 fs-6">
                Esta acción aprobará la solicitud y aumentarán los días solicitados al prestamo.
                Nueva fecha de devolución: 
                <br>
                <strong>{{ $nuevaFechaDevolucion }}</strong>
                
            </p>
            

            <form method="POST" action="{{ route('admin.prestamos.aumentarPlazo.aprobar', [$id, $nuevaFechaDevolucion]) }}">
                @csrf
                <button type="submit" class="btn btn-success btn-lg w-100 mb-3 shadow-sm" style="font-weight: 600; letter-spacing: 0.03em;">
                   Confirmar aprobación
                </button>
            </form>

            <a href="{{ route('prestamos.index') }}" class="btn btn-outline-secondary btn-lg w-100" style="font-weight: 600;">
                Cancelar
            </a>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ mix('js/app.js') }}"></script>
@endsection
