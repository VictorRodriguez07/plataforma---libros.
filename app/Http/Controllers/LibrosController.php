<?php

namespace App\Http\Controllers;
use App\CategoriaLibro;
use App\genero;
use App\Libro;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class LibrosController extends Controller
{
 
    //Obtiene los datos necesarios de la base de datos para insertar un nuevo libro
    public function getCreate(){
        $generos = genero::all();
        $categorias = CategoriaLibro::all();
        return view('admin.libros.crear', compact('generos', 'categorias'));
        
    }

    //Funcion Store que valida por medio del Validator. Si todo esta bien, inserta el libro en la base de datos, sino muestra un mensaje de error donde falló la validación
    public function store(Request $request){
        $libro = new Libro();
        $validator= Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'autor' => 'string|max:255',
            'sinopsis' => 'required|string',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cantidad' => 'required|integer|min:1',
            'id_genero' => 'required|exists:generos_libros,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $libro->nombre = $request->input('nombre');
            $libro->fecha_publicacion = $request->input('fecha_publicacion');
            $libro->autor = $request->input('autor');
            $libro->sinopsis = $request->input('sinopsis');
            $libro->img = $request->file('img')->store('images', 'public');
            $libro->cantidad = $request->input('cantidad');
            $libro->id_genero = $request->input('id_genero');
            $libro->id_estado = 6; 
            $libro->save();
    
            return redirect()->route('admin.libros.crear')->with('success', 'Libro creado exitosamente.');
        }
       
        
    }

    //Retorna la view del consult del libro junto con los datos de la base de datos
    public function getIndex(){
        $libros = Libro::all();
        return view('admin.libros.index', compact('libros'));
    }

    //Función get que obtiene la información de los libros para retornar la view del catálogo con los datos necesarios.
    public function getCatalogo(){
        $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'libros.id_estado',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre'
            
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
        ->limit(10)
        ->get();
         $generos = genero::all();
        $categorias = CategoriaLibro::all();
        
        return view('admin.libros.catalogo', compact('libros', 'generos', 'categorias'));
    }
    
    //Función get que recibe el id del libro seleccionado y, con base en este, se realiza la consulta para obtener el detalle del libro seleccionado. retornado la vista del detalle del libro seleccionado y su información.
    public function getDetalle($id){
     
        $libro = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre',
            'libros.id_estado',
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
        ->where('libros.id', $id)
        ->first();        
    return $libro;   
    }

    //funcion get Obtiene los datos necesarios de la base de datos para editar el libro seleccionado

    public function getEditar($id){
     
        $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'generos_libros.nombre as genero_nombre'
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        
        ->where('libros.id', $id)
        ->first();  
        $generos = genero::all();
        return $datos=['libros' => $libros,
            'generos' => $generos
        ];      

    }

    

    public function getLibros(){
        $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre',
            'libros.id_estado',
            'estados_solicitudes_prestamos.nombre as estado_nombre'
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
        ->join('estados_solicitudes_prestamos', 'libros.id_estado', '=', 'estados_solicitudes_prestamos.id')
        ->orderBy('libros.id', 'asc')
        ->get();

       
        
        return view('admin.libros.listar', compact('libros'));
    }

    //Actualiza el libro seleccionado por medio del ID del libro enviado como parámetro y la request enviada como parámetro

    public function update(Request $request, $id){
        $libro = Libro::find($id);
        $data=$request->all();
        $rules=[];
        if (isset($data['nombre'])) $rules['nombre'] = 'required|string';
        if (isset($data['autor'])) $rules['autor'] = 'required|string';
        if (isset($data['fecha_publicacion'])) $rules['fecha_publicacion'] = 'required|date';
        if (isset($data['id_genero'])) $rules['id_genero'] = 'required|exists:generos,id';
        if (isset($data['sinopsis'])) $rules['sinopsis'] = 'required|string';
        if (isset($data['cantidad'])) $rules['cantidad'] = 'required|integer|min:1';
        if (isset($data['img'])) {
            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $libro->update($validator->validated());
              $libro = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre'
            )
            ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
            ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
            ->get();
            if($libro){
                return $libro;
            }else{
                return 2;
            }
            

       
    }
}



public function cargarLibros(){
    $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'libros.id_estado',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre'
            
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
        ->get();
        
        return $libros;
}

//Funcion de devuelve un libro por nombre el cual es enviado como parámetro
public function buscarNombreLibro($nombre){
        $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'libros.id_estado',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre'
            
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
         ->where('libros.nombre', 'like', '%' . $nombre . '%')
        ->get();
        
        return $libros;
        
    }

    //función que devuelve el libro por su id de genero enviado como parámetro

    public function buscarGeneroLibro($id_genero){

        $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'libros.id_estado',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre'
            
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
         ->where('libros.id_genero', 'like', '%' . $id_genero . '%')
        ->get();
        
        return $libros;
    }

        //función que devuelve el libro por su id de estado enviado como parámetro

    public function buscarDisponibilidadLibro($id_estado){

        $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'libros.id_estado',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre'
            
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
         ->where('libros.id_estado', $id_estado)
        ->get();
        
        return $libros;
    }

    //Funcion get que consulta los libros de la bd, saltandóse los libros que ya han sido cargados y los retorna para ser pintados en el component.(Funciona como paginado)

    public function getCatalogoPaginate($offset){
        $libros = Libro::select(
            'libros.id',
            'libros.nombre',
            'libros.fecha_publicacion',
            'libros.autor',
            'libros.sinopsis',
            'libros.img',
            'libros.cantidad',
            'libros.id_genero',
            'libros.id_estado',
            'generos_libros.nombre as genero_nombre',
            'categorias_libros.nombre as categoria_nombre'
            
        )
        ->join('generos_libros', 'libros.id_genero', '=', 'generos_libros.id')
        ->join('categorias_libros', 'generos_libros.id_categoria', '=', 'categorias_libros.id')
        ->skip($offset)
        ->take(10)
        ->get();
        
        return $libros;
    }
    
}

