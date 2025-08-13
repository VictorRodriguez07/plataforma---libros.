@extends('adminlte::page')
@section('title', 'Listado de libros')
@section('content')
<div id="app">
    <br>
    <listar :listar_libros='@json($libros)'></listar>
</div>
@endsection
@section('js')
<script src="{{ mix('js/app.js') }}"></script>
@endsection





{{-- @extends('adminlte::page')
@section('title', 'Ver libros')
@section('content')
<div class="libros pt-3">
    <div class="row">
        @if (session('status'))
            <div class="col-md-12">
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header with-border text-center">
                    <h2 class="card-title">Libros</h2>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped" id="libros-table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Libro</th>
                                <th class="text-center">Título</th>
                                <th class="text-center">Autor</th>
                                <th class="text-center">Fecha de publicación</th>
                                <th class="text-center">Género</th>
                                <th class="text-center">Categoría</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                                @foreach ($libros as $libro)
                                <tr>
                                    <td class="text-center">{{ $libro->id }}</td>
                                    <td class="text-center"><img src="{{ asset('storage/'.$libro->img) }}" alt="Portada" width="50" height="100"></td>
                                    <td class="text-center">{{ $libro->nombre }}</td>
                                    <td class="text-center">{{ $libro->autor }}</td>
                                    <td class="text-center">{{ $libro->fecha_publicacion }}</td>
                                    <td class="text-center">{{ $libro->genero_nombre }}</td>
                                    <td class="text-center">{{ $libro->categoria_nombre }}</td>
                                    <td class="text-center">{{ $libro->cantidad }}</td>
                                    <td class="text-center" style="min-width: 365px;">
                                    <a href="" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Editar</a>
                                    {!! Form::open([]) !!}
                                                <button class="btn btn-danger btn-xs" ><i class="fas fa-trash"></i> Eliminar</button>
                                        {!! Form::close()!!}
                                    </td>
                                </tr>
                                    @endforeach
                                    
                           

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}