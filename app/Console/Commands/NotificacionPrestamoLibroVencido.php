<?php

namespace App\Console\Commands;

use App\PrestamoLibro;
use Mail;
Use App\User;
use Illuminate\Console\Command;

class NotificacionPrestamoLibroVencido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notificacionPrestamoLibroVencido';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a los usuarios sobre préstamos de libros vencidos';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $prestamoVencido=PrestamoLibro::select('prestamos_libros.id','prestamos_libros.fecha_devolucion','prestamos_libros.id_solicitud_prestamo','u.nombre as nombre_usuario', 'u.email as email_usuario','l.nombre as nombre_libro','l.img as img_libro','spl.id_estado')
            ->join('solicitudes_prestamos_libros as spl', 'spl.id', '=', 'prestamos_libros.id_solicitud_prestamo')
            ->join('libros as l', 'l.id', '=', 'spl.id_libro')
            ->join('users as u', 'u.id', '=', 'spl.id_usuario')
            ->where('prestamos_libros.fecha_devolucion', '<', '2025-06-05')
            ->where('spl.id_estado', '!=', 5)
            ->where('spl.id_estado', '!=', 8)
            ->get();
            if($prestamoVencido){
                foreach ($prestamoVencido as $prestamo) {
                    $data = [
                        'nombre_usuario' => $prestamo->nombre_usuario,
                        'nombre_libro' => $prestamo->nombre_libro,
                        'img_libro' => $prestamo->img_libro,
                        'fecha_devolucion' => $prestamo->fecha_devolucion,
                    ];
                    Mail::send('emails.solicitudes_prestamos_libros.prestamo_vencido', $data, function ($message) use ($prestamo) {
                        $message->to($prestamo->email_usuario)
                                ->subject('¡Préstamo de libro vencido!');
                    });
                }
                $this->info('Notificaciones de préstamos vencidos enviadas correctamente.');
            }
    }
}
