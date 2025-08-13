<?php

namespace App\Http\Controllers;

use App\Libro;
use App\PrestamoLibro;
use App\SolicitudPrestamoLibro;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Gate;
use Illuminate\Support\Facades\DB;

class PrestamosLibrosController extends Controller
{
    //funcion get que obtiene las solicitudes de prestamos realizdas que se encuentran en la base de datos. 
    // se hace una condición con el Gate para verificar si el usuario tiene el permiso de aprobar las solicitudes de prestamos. 
    // por último, se retorna la view correspondiente junto a la información.

    public function getPrestamo()
    {
        $prestamos = SolicitudPrestamoLibro::select(
        'solicitudes_prestamos_libros.id',
        'solicitudes_prestamos_libros.fecha_solicitud',
        'solicitudes_prestamos_libros.direccion_envio',
        'libros.nombre as nombre_libro',
        'libros.img as img_libro',
        'users.nombre as nombre_usuario',
        'users.apellido',
        'users.email as email_usuario',
        'solicitudes_prestamos_libros.id_estado',
        'estados_solicitudes_prestamos.nombre as nombre_estado',
        'estados_solicitudes_prestamos.id as id_estado_solicitud',
        'prestamos_libros.fecha_devolucion'
    )
    ->join('libros', 'libros.id', '=', 'solicitudes_prestamos_libros.id_libro')
    ->join('users', 'users.id', '=', 'solicitudes_prestamos_libros.id_usuario')
    ->join('estados_solicitudes_prestamos', 'estados_solicitudes_prestamos.id', '=', 'solicitudes_prestamos_libros.id_estado')
    ->leftJoin(DB::raw('(
            SELECT id_solicitud_prestamo, MAX(fecha_devolucion) as fecha_devolucion
            FROM prestamos_libros
            GROUP BY id_solicitud_prestamo
        ) as prestamos_libros'), 'prestamos_libros.id_solicitud_prestamo', '=', 'solicitudes_prestamos_libros.id');


    if (!Gate::forUser(auth()->user())->allows('aprobar-solicitud-prestamo')) {
        $prestamos = $prestamos->where('solicitudes_prestamos_libros.id_usuario', auth()->user()->id);
    }

    $prestamos = $prestamos->get();
        return view('admin.prestamos_libros.listar', compact('prestamos'));
    }
    public function getCreate()
    {
        return view('admin.prestamos.crear');
    }
    public function getEdit()
    {
        return view('admin.prestamos.editar');
    }

    //funcion Store la cual se encarga de guardar la solicitud de prestamo en la base de datos. 
    //al guardar la solicitud del libro, se envía un correo con los datos de la solicitud realizada al encargado de aprobar o denegar 
    // la solicitud.
public function store(Request $request)
{
    $request->validate([
        'libro_id' => 'required|exists:libros,id',
        'enTqi' => 'required|in:si,no',
        'direccion' => 'nullable|required_if:enTqi,no|min:4',
        'dias' => 'required|integer|min:1',
    ]);

    $solicitudPrestamo = new SolicitudPrestamoLibro();
    $solicitudPrestamo->fecha_solicitud = now();

    if ($request->input('enTqi') == 'no') {
        $solicitudPrestamo->direccion_envio = $request->input('direccion');
    }

    $solicitudPrestamo->id_libro = $request->input('libro_id');
    $solicitudPrestamo->id_usuario = auth()->user()->id;
    $solicitudPrestamo->id_estado = 1;
    $solicitudPrestamo->dias_solicitados = $request->input('dias');
    $solicitudPrestamo->save();

    if ($solicitudPrestamo) {

        $libro=Libro::where('id',$request->input('libro_id'))->update([
            'id_estado' => 7
        ]);
        $libro = Libro::select('id', 'nombre', 'img')->find($request->input('libro_id'));
        echo auth()->user()->nombre;
       

        $datos = [
            'nombre_usu' => auth()->user()->nombre,
            'email' => auth()->user()->email,
            'nombre_libro' => $libro->nombre,
            'id_libro' => $libro->id,
            'fechaDevolucion' => now()->addDays($request->dias)->format('d/m/Y'),
            'img_libro' => 'cid:imagenLibro',
            'url_prestamo_detalle' => url('admin/prestamos/solicitud/' . $solicitudPrestamo->id . '/confirmar'),

            'url_prestamo_detalle_denegar' => url('admin/prestamos/solicitud/' . $solicitudPrestamo->id . '/confirmarRechazo'),
        ];

        Mail::send('emails.solicitudes_prestamos_libros.solicitud', $datos, function ($message) use ($datos, $libro) {
            $message->to('victor.rodriguez@tqi.co', 'Administrador TQI')
                    ->subject('Solicitud de préstamo de ' . $datos['nombre_usu']);

             if (file_exists(storage_path('app/public/' . $libro->img))) {
                $message->embed(storage_path('app/public/' . $libro->img), 'imagenLibro');
            }


            
        });

        return "ok";
    }

    return response()->json(['status' => 'error'], 500);
}

public function mostrarAprobarSolicitudPrestamoLibro($id)
{
    
    return view('admin.prestamos_libros.mostrarConfirmacion', compact('id'));
}

public function mostrarRechazarSolicitudPrestamoLibro($id)
{
    
    return view('admin.prestamos_libros.mostrarConfirmacionRechazo', compact('id'));
}

public function mostrarAprobarAumentarPlazoPrestamoLibro($id, $nuevaFechaDevolucion)
{
    $solicitud = PrestamoLibro::find($id);
    if ($solicitud) {
        return view('admin.prestamos_libros.mostrarConfirmacionAumentoPlazo', compact('id', 'nuevaFechaDevolucion'));
    }else{
        return "error";
    }
    
}


public function mostrarRechazarAumentarPlazoPrestamoLibro($id)
{
    $solicitud = PrestamoLibro::find($id);
   
        return view('admin.prestamos_libros.mostrarRechazoAumentoPlazo', compact('id'));
    
    
}

//Funcion que aprueba la solicitud  de aumento del tiempo de plazo de un prestamo de libro, por medio del cambio de estado en la base de datos
//  y envía un correo electrónico a la persona que solicitó el aumento de tiempo de plazo, notificando su aprobación.
public function aprobarAumentoPlazoPrestamoLibro($id,$nuevaFechaDevolucion)
{
    $prestamo = PrestamoLibro::find($id);
    if ($prestamo) {
        $prestamo->fecha_devolucion = Carbon::parse($nuevaFechaDevolucion);
       if($prestamo->save()){
        $solicitud = SolicitudPrestamoLibro::find($prestamo->id_solicitud_prestamo);
        $libro = Libro::find($solicitud->id_libro);
        
        $resumen = 'Respuesta Solicitud de aumento de plazo de préstamo';

            $data = [
                'nombre_libro' => $libro->nombre,
                'img_libro' => $libro->img ?? 'https://via.placeholder.com/120x160?text=Sin+imagen',
                'fechaDevolucion' => $prestamo->fecha_devolucion,
                'resumen' => $resumen,
            ];

            Mail::send('emails.solicitudes_prestamos_libros.aprobacionAumentoPlazoPrestamo', $data, function ($message) {
                $message->to('victor.rodriguez@tqi.co')
                    ->subject('Respuesta Solicitud de aumento de plazo de préstamo');
            });

           return redirect()->route('admin.prestamos.listar')->with('success', 'Aumento de plazo aprobado exitosamente.');
        } else {
            return redirect()->route('admin.prestamos.listar')->with('error', 'Error al aprobar el aumento de plazo.');
        }
    }

   

    
}

//Funcion que aprueba la solicitud del prestamo de libro, por medio del cambio de estado en la base de datos
//  y envía un correo electrónico a la persona que solicitó el prestamo notificando su aprobación.
public function aprobarSolicitudPrestamoLibro($id)
{
    $solicitud = SolicitudPrestamoLibro::find($id);
    if ($solicitud) {
        $solicitud->id_estado = 2;
        if($solicitud->save()){
            $prestamo_detalle=new PrestamoLibro();
            
            $prestamo_detalle->fecha_devolucion = Carbon::parse($solicitud->fecha_solicitud)->addDays($solicitud->dias_solicitados);



            $prestamo_detalle->id_solicitud_prestamo=$solicitud->id;
            
            if($prestamo_detalle->save()) {
                
                return redirect()->route('admin.prestamos.listar')->with('ok');
            } else {
                return redirect()->route('admin.prestamos.listar')->with('error', 'Error al registrar el préstamo.');
            }
        }

        
    }

    return redirect()->route('prestamos.index')->with('error', 'Solicitud no encontrada.');


}

//funcion que rechaza la solicitud de prestamo de libro, por medio del cambio de estado en la base de datos 
//y envía un correo a la persona que ha solicitado el prestampo, informando sobre el rechazo y el motivo de este.
public function rechazarSolicitudPrestamoLibro($id)
{
    $solicitud = SolicitudPrestamoLibro::find($id);
    if ($solicitud) {
        $solicitud->id_estado = 3;
        if($solicitud->save()){
            $libro = Libro::find($solicitud->id_libro);
            if ($libro) {
                $libro->id_estado = 6;
                $libro->save();
            }
            return redirect()->route('admin.prestamos.listar')->with('success', 'Solicitud rechazada y libro disponible nuevamente.');
        } else {
            return redirect()->route('admin.prestamos.listar')->with('error', 'Error al rechazar la solicitud.');
        }

        
    }

    return redirect()->route('prestamos.index')->with('error', 'Solicitud no encontrada.');


}

//funcion que da continuidad al flujo de vida del prestamo de un libro. 
// Esta funcion se encarga de modificar el estado de un prestamo a "entregado", dando a entender que el libro ya se encuentra en manos del usuario que lo solicitó.

public function marcarEntregadoPrestamoLibro($id)
{
            $solicitud = SolicitudPrestamoLibro::find($id);
            if ($solicitud) {
                $solicitud->id_estado = 4;
                if($solicitud->save()){
                    return "ok";
                }else {
            return redirect()->route('admin.prestamos.listar')->with('error', 'Error al marcar el préstamo como entregado.');
        }
         return redirect()->route('admin.prestamos.listar')->with('error', 'Préstamo no encontrado.');
    }else{
        return redirect()->route('admin.prestamos.listar')->with('error', 'Solicitud no encontrada.');
    }
    }

    //funcion que se encarga de registrar una solicitud de aumento de tiempo de plazo de entrega de un prestamo de libro en la base de datos.
    public function aumentarPlazoPrestamoLibro(Request $request, $id)
    {
        $request->validate([
            'dias_extra' => 'required|integer|min:1',
        ]);

        $solicitud = SolicitudPrestamoLibro::find($id);
        if ($solicitud) {
             

            $solicitud->dias_solicitados = $request->dias_extra;
           $prestamo=PrestamoLibro::where('id_solicitud_prestamo', $solicitud->id)->first();
            $nuevaFechaDevolucion = Carbon::parse($prestamo->fecha_devolucion)->addDays($request->dias_extra)->format('Y-m-d');
            $nombre_usu= User::select('nombre')
            ->where('id', $solicitud->id_usuario)->first();
            $email_usu=User::select('email')
            ->where('id', $solicitud->id_usuario)->first();
            $nombre_libro=Libro::select('nombre')
            ->where('id', $solicitud->id_libro)->first();
            $id_prestamo = PrestamoLibro::where('id_solicitud_prestamo', $solicitud->id)
            ->value('id');


            $resumen = 'Solicitud de aumento de plazo de préstamo';

            $data = [
                'nombre_usu' => $nombre_usu->nombre,
                'email' => $email_usu->email,
                'nombre_libro' => $nombre_libro->nombre,
                'id_libro' => $solicitud->id_libro,
                'img_libro' => $solicitud->libro->imagen_url ?? 'https://via.placeholder.com/120x160?text=Sin+imagen',
                'dias_extra' => $request->dias_extra,
                'fechaDevolucion' => $nuevaFechaDevolucion,
                'url_prestamo_detalle' => url('admin/prestamos/aumentarPlazo/' . $id_prestamo . '/'.$nuevaFechaDevolucion.'/confirmar'),
                'url_prestamo_detalle_denegar' => url('admin/prestamos/aumentarPlazo/' . $id_prestamo . '/confirmarRechazo'),
                'resumen' => $resumen,
            ];

            Mail::send('emails.solicitudes_prestamos_libros.solicitudAumentoPlazo', $data, function ($message) {
                $message->to('victor.rodriguez@tqi.co')
                    ->subject('Solicitud de aumento de plazo');
            });

    return "ok";
        } else {
            return "error";
    }
}


    //Función encargada de dar continuidad al flujo de vida de un prestamo de libro. Notifica a al empresa que el usuario ya ha devuelto el libro a las instalaciones de al empresa.
    public function notificarDevolucionLibro(Request $request,$id)
    {
        $request->validate([
            'fecha_devolucion' => 'required',
            'foto_evidencia' => 'nullable|image|max:2048',
        ]);
        $solicitud = SolicitudPrestamoLibro::find($id);
        if ($solicitud) {
            $solicitud->id_estado = 8; 
            if ($solicitud->save()) {
                return "ok";
            } else {
                return "error";
            }
        }

        return "error";
    }


    //Funcion que rechaza la solicitud  de aumento del tiempo de plazo de un prestamo de libro, por medio del cambio de estado en la base de datos
    //  y envía un correo electrónico a la persona que solicitó el aumento de tiempo de plazo, notificando su rechazdo y motivo de este.

    public function rechazarAumentoPlazoPrestamoLibro($id){
        $prestamo = PrestamoLibro::find($id);
        $solicitud = SolicitudPrestamoLibro::find($prestamo->id_solicitud_prestamo);
        $libro = Libro::find($solicitud->id_libro);

        $resumen = 'Respuesta Solicitud de aumento de plazo de préstamo';

            $data = [
                'nombre_libro' => $libro->nombre,
                'img_libro' => $libro->img ?? 'https://via.placeholder.com/120x160?text=Sin+imagen',
                'fechaDevolucion' => $prestamo->fecha_devolucion,
                'resumen' => $resumen,
            ];

            Mail::send('emails.solicitudes_prestamos_libros.rechazoAumentoPlazoPrestamo', $data, function ($message) {
                $message->to('victor.rodriguez@tqi.co')
                    ->subject('Respuesta Solicitud de aumento de plazo de préstamo');
            });

    return "ok";


        
    }

    //funcion encargada de finalizar el ciclo de vida de un prestamo. Genera el cambio de estado del prestamo a finalizado y la disponibilidad del libro a disponible.
    public function finalizarPrestamoLibro($id){
       
        $solicitud = SolicitudPrestamoLibro::find($id);
        if($solicitud) {
           
            $solicitud->id_estado = 5; 
            $solicitud->save();
            $libro = Libro::find($solicitud->id_libro);
            if ($libro) {
                $libro->id_estado = 6;
                $libro->save();
            }

            return "ok";
        } else {
            return "error";
        }
    }
}

   




