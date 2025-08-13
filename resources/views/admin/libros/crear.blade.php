@extends('adminlte::page')
@section('title', 'Crear libro')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
<div class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ingresar libro</div>
                <div class="card-body">

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    <form method="POST" action="{{route('admin.libros.store')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="nombre">Nombre del libro</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>

                        <div class="form-group">
                            <label for="fecha_publicacion">Fecha de publicación</label>
                            <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion">
                        </div>

                        <div class="form-group">
                            <label for="autor">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor">
                        </div>

                        <div class="form-group">
                            <label for="sinopsis">Sinopsis</label>
                            <textarea class="form-control" id="sinopsis" name="sinopsis" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="img">Imagen</label>
                            {{-- <input type="text" class="form-control mb-2" id="img" name="img" placeholder="URL de la imagen"> --}}
                            {{-- O si prefieres subir archivo: --}}
                            <input type="file" class="form-control" id="img" name="img" class="form-control mb-2">
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1">
                        </div>

                        <div class="form-group">
                            <label for="categoria_id">Categoría</label>
                            <select class="form-control" id="categoria_id" name="categoria_id">
                                <option value="">Seleccione una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="genero_id">Género</label>
                            <select class="form-control" id="id_genero" name="id_genero" required>
                                <option value="">Seleccione un género</option>
                                @foreach($generos as $genero)
                                    <option value="{{ $genero->id }}" data-categoria="{{ $genero->id_categoria }}">{{ $genero->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear libro</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Filtrar géneros por categoría seleccionada
document.addEventListener('DOMContentLoaded', function () {
    const categoriaSelect = document.getElementById('categoria_id');
    const generoSelect = document.getElementById('genero_id');
    const allGeneroOptions = Array.from(generoSelect.options);

    categoriaSelect.addEventListener('change', function () {
        const categoriaId = this.value;
        generoSelect.innerHTML = '<option value="">Seleccione un género</option>';
        allGeneroOptions.forEach(option => {
            if (!option.value) return; // Saltar opción vacía
            if (option.getAttribute('data-categoria') === categoriaId) {
                generoSelect.appendChild(option.cloneNode(true));
            }
        });
    });
});
</script>
@endsection
