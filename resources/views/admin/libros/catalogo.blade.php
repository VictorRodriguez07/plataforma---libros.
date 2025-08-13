@extends('adminlte::page')
@section('title', 'Catálogo de libros')
@section('content')
<div id="app">
   <br>
    <libros :libros-iniciales='@json($libros)' :generos-iniciales='@json($generos)' :categorias-iniciales='@json($categorias)' ></libros>
</div>
@endsection
@section('js')
<script src="{{ mix('js/app.js') }}"></script>
@endsection