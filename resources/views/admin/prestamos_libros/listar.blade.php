@extends('adminlte::page')
@section('title', 'Listado de prestamos de libros')
@section('content')
<div id="app">
    <br>
   <listar-prestamos-libros :listar-prestamos-libros='@json($prestamos)'
   v-bind:permisos_marcar_entregado="{{ json_encode(Gate::forUser(auth()->user())->allows('marcar-prestamo-entregado'))}}"
    v-bind:permisos_aprobar_prestamos="{{ json_encode(Gate::forUser(auth()->user())->allows('aprobar-prestamos'))}}"

   ></listar-prestamos-libros>
   

</div>
@endsection
@section('js')
<script src="{{ mix('js/app.js') }}"></script>
@endsection

