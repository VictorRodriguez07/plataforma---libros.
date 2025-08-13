<template>
     <div class="card card-primary card-outline">
        <div class="card-header with-border text-center">
          <h2 class="card-title">Solicitudes de prestamos de libros</h2>
        </div>
        <div class="card-body table-responsive">
           <div v-if="prestamos.length > 0">
          <table class="table table-bordered table-striped" id="libros-table">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Portada</th>
                <th class="text-center">Título</th>
                <th class="text-center">Solicitante</th>
                <th class="text-center">Fecha de solicitud</th>
                <th class="text-center">Fecha de devolución</th>
                <th class="text-center">Estado de prestamo</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="prestamo in prestamos" :key="prestamo.id">
                <td class="text-center">{{ prestamo.id }}</td>
                <td class="text-center">
                  <img
                    :src="getImageUrl(prestamo.img_libro)"
                    alt="Portada"
                    width="50"
                    height="100"
                  />
                </td>
                <td class="text-center align-middle">{{ prestamo.nombre_libro}}</td>
                <td class="text-center align-middle">{{ prestamo.nombre_usuario }} {{ prestamo.apellido }}</td>
                <td class="text-center align-middle">{{ prestamo.fecha_solicitud }}</td>
                <td class="text-center align-middle">{{ prestamo.fecha_devolucion ? prestamo.fecha_devolucion : 'N/A' }}</td>
                <td class="text-center align-middle">{{ prestamo.nombre_estado }}</td>  
                 <td class="text-center align-middle">
                  <div class="btn-group btn-group-sm" role="group">
                    <!-- <button v-if="permisos_marcar_entregado && prestamo.id_estado_solicitud!=5" class="btn btn-primary" @click="editarPrestamo(prestamo.id)">
                      <i class="fas fa-edit"></i> Editar
                    </button>
                    <button v-if="permisos_marcar_entregado && prestamo.id_estado_solicitud!=5" class="btn btn-danger" @click="eliminarPrestamo(prestamo.id)">
                      <i class="fas fa-trash"></i> Eliminar
                    </button> -->

                    <button v-if="prestamo.id_estado_solicitud == 1 && permisos_marcar_entregado" class="btn btn-success" @click="aprobarPrestamo(prestamo.id)">
                      <i class="fas fa-check"></i> Aprobar Prestamo
                    </button>

                    <button v-if="prestamo.id_estado_solicitud == 1 && permisos_marcar_entregado" class="btn btn-danger" @click="rechazarPrestamo(prestamo.id)">
                      <i class="fas fa-times"></i> Rechazar Prestamo

                    </button>

                    <button v-if="prestamo.id_estado_solicitud == 2 && permisos_marcar_entregado" class="btn btn-success" @click="marcarEntregado(prestamo.id)">
                      <i class="fas fa-check"></i> Entregado
                    </button>
                    <button v-if="prestamo.id_estado_solicitud == 4 && permisos_marcar_entregado" class="btn btn-warning" @click="mostrarNotificarDevolucion(prestamo.id)">
                      <i class="fas fa-undo"></i> Notificar Devolución
                    </button>
                    <button v-if="prestamo.id_estado_solicitud == 4 && permisos_marcar_entregado" class="btn btn-info" @click="mostrarAumentarPlazo(prestamo.id)">
                      <i class="fas fa-clock"></i> Aumentar plazo
                    </button>
                    <button v-if="prestamo.id_estado_solicitud == 8 && permisos_marcar_entregado" class="btn btn-dark" @click="finalizarPrestamo(prestamo.id)">
                      <i class="fas fa-times-circle"></i> Finalizar Prestamo
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          </div>
           <div v-else class="d-flex flex-column justify-content-center align-items-center p-5 text-muted">
              <i class="fas fa-book-reader fa-4x mb-3" style="color: #6c757d;"></i>
              <h4 class="mb-2">No has solicitado ningún libro todavía</h4>
              <p class="text-center mb-4">
                Tu estantería está vacía por ahora. Explora el catálogo y encuentra tu próxima lectura.
              </p>
              <a href="../libros/catalogo" class="btn btn-outline-primary btn-lg">
              <i class="fas fa-book-open mr-2"></i> Explorar Catálogo
              </a>
          </div>


        </div>
        

  <div v-if="flagNotificarDevolucion" class="modal" style="display:block; background:rgba(0,0,0,0.5); position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050;" role="dialog" aria-modal="true" aria-labelledby="notificarDevolucionTitle">
  <div class="modal-dialog modal-lg" style="margin:10% auto;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notificarDevolucionTitle">Notificar devolución</h5>
        <button type="button" class="close" @click="cerrarModalNotificarEnvio()"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="notificarDevolucion" enctype="multipart/form-data">
          <p>Por favor, complete la información para notificar la devolución del libro.</p>
          <div class="form-group"><label for="fechaDevolucion">Fecha de devolución</label><input v-model="fechaDevolucion" type="date" class="form-control" id="fechaDevolucion" required /></div>
          <div class="form-group"><label for="fotoEvidencia">Foto de evidencia</label><input type="file" @change="handleFileChange" /></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModalNotificarEnvio()">Cerrar</button>
            <button type="submit" class="btn btn-primary">Notificar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


 <div v-if="flagAumentarPlazo" class="modal" style="display:block; background:rgba(0,0,0,0.5); position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050;" role="dialog" aria-modal="true" aria-labelledby="notificarDevolucionTitle">
    <div class="modal-dialog modal-lg" style="margin:10% auto;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notificarDevolucionTitle">Solicitar aumento de plazo</h5>
        <button type="button" class="close" @click="cerrarModalAumentarPlazo()"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form @submit.prevent="aumentarPlazoPrestamo">
          <p>Por favor, ingrese la cantidad de días que desea aumentar el plazo del prestamo.</p>
          <div class="form-group"><label for="fechaDevolucion">Cantidad de días*</label><input v-model="cantidadDias" type="number" class="form-control" id="cantidadDias" required /></div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModalAumentarPlazo()">Cerrar</button>
            <button type="submit" class="btn btn-primary">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

      </div>
</template>

<script>
  import axios from 'axios';
  import Swal from 'sweetalert2';
  export default {
    name: 'listar-prestamos-libros',
props: {
  listarPrestamosLibros: {
    type: Array,
    default: () => []
  },
  permisos_marcar_entregado: {
    type: Boolean,
    default: false
  }
},
mounted() {
    console.log('Prestamos recibidos:', this.listarPrestamosLibros);
    console.log('Permiso para marcar entregado:', this.permisos_marcar_entregado);
  },

    data() {
  return {
    prestamos: [...this.listarPrestamosLibros],
    flagNotificarDevolucion: false,
    flagAumentarPlazo: false,
    fechaDevolucion: '',
    fotoEvidencia: null ,
    idSolicitudPrestamo: 0,
    cantidadDias: 0,
  };
},

    methods: {
      getImageUrl(path) {
        return `/storage/${path}`;
      },
      marcarEntregado(id) {
        this.$swal({
          title: 'Confirmar',
          text: '¿Estás seguro de marcar este préstamo como entregado?',
          icon: 'warning',
          buttons: ['Cancelar', 'Sí, marcar como entregado'],
          dangerMode: false,
        })
        .then((willConfirm) => {
          if (willConfirm) {
             axios.patch(`/admin/prestamos/libros/${id}/marcarEntregado`)
              .then(response => {
                if (response.data.includes('ok')) {
                  this.$swal('Éxito', 'El préstamo ha sido marcado como entregado.', 'success');
                  const prestamo = this.prestamos.find(p => p.id === id);
                    if (prestamo) {
                      prestamo.nombre_estado = 'Realizado';
                      prestamo.id_estado_solicitud = 4;
                    }
                    
                } else {S
                  this.$swal('Error', 'No se pudo marcar el préstamo como entregado.', 'error');
                }
              })
              .catch(error => {
                console.error(error);
                this.$swal('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
              });
          }
        });

      },
      mostrarNotificarDevolucion(id){
        this.flagNotificarDevolucion = true;
        this.idSolicitudPrestamo = id;

      },
      cerrarModalNotificarEnvio() {
        this.flagNotificarDevolucion = false;
        this.idSolicitudPrestamo = 0;
      },
      handleFileChange(event) {
    this.fotoEvidencia = event.target.files[0];
  },
    notificarDevolucion() {
  if (!this.fechaDevolucion || !this.fotoEvidencia) {
    Swal.fire({
      icon: 'warning',
      title: 'Campos incompletos',
      text: 'Por favor, complete todos los campos.',
    });
    return;
  }

  const formData = new FormData();
  formData.append('fecha_devolucion', this.fechaDevolucion);
  formData.append('foto_evidencia', this.fotoEvidencia);

  Swal.fire({
    title: 'Enviando notificación...',
    text: 'Por favor espera.',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  axios.post(`/admin/prestamos/notificarDevolucionLibro/${this.idSolicitudPrestamo}`, formData)
    .then(res => {
      Swal.close();

      if (res.data === 'ok') {
        Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'Devolución notificada correctamente.',
        });

        const prestamo = this.prestamos.find(p => p.id === this.idSolicitudPrestamo);
        if (prestamo) {
          prestamo.nombre_estado = 'En devolución';
          prestamo.id_estado_solicitud = 8;
        }
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al notificar la devolución.',
        });
      }

      this.cerrarModalNotificarEnvio();
    })
    .catch(err => {
      Swal.close();
      Swal.fire({
        icon: 'error',
        title: 'Error de red',
        text: 'No se pudo contactar con el servidor.',
      });
      console.error(err);
    });
},

      mostrarAumentarPlazo(id) {
       this.flagAumentarPlazo = true;
        this.idSolicitudPrestamo = id;
      },
      cerrarModalAumentarPlazo() {
        this.flagAumentarPlazo = false;
        this.idSolicitudPrestamo = 0;
      },
      aumentarPlazoPrestamo() {
  if (!this.cantidadDias || this.cantidadDias <= 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Cantidad no válida',
      text: 'Por favor, ingrese una cantidad válida de días.',
    });
    return;
  }

  const formData = new FormData();
  formData.append('dias_extra', this.cantidadDias);

  Swal.fire({
    title: 'Enviando solicitud...',
    text: 'Por favor espera.',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  axios.post(`/admin/prestamos/aumentarPlazo/${this.idSolicitudPrestamo}`, formData)
    .then(res => {
      Swal.close();

      if (res.data === 'ok') {
        Swal.fire({
          icon: 'success',
          title: 'Solicitud enviada',
          text: 'La solicitud de aumento de plazo se ha enviado correctamente.',
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No se pudo aumentar el plazo del préstamo.',
        });
      }

      this.cerrarModalAumentarPlazo();
    })
    .catch(err => {
      Swal.close();
      Swal.fire({
        icon: 'error',
        title: 'Error de red',
        text: 'Hubo un error al enviar la solicitud.',
      });
      console.error(err);
    });
},

      finalizarPrestamo(id) {
        this.$swal({
          title: 'Confirmar',
          text: '¿Estás seguro de finalizar este préstamo?',
          icon: 'warning',
          buttons: ['Cancelar', 'Sí, finalizar'],
          dangerMode: false,
        })
        .then((willConfirm) => {
          if (willConfirm) {
            axios.patch(`/admin/prestamos/libros/${id}/finalizar`)
              .then(response => {
                if (response.data.includes('ok')) {
                  this.$swal('Éxito', 'El préstamo ha sido finalizado.', 'success');
                  const prestamo = this.prestamos.find(p => p.id === id);
                    if (prestamo) {
                      prestamo.nombre_estado = 'Finalizado';
                      prestamo.id_estado_solicitud = 5;
                    }
                } else {
                  this.$swal('Error', 'No se pudo finalizar el préstamo.', 'error');
                }
              })
              .catch(error => {
                console.error(error);
                this.$swal('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
              });
          }
        });
      },
      aprobarPrestamo(id) {
        this.$swal({
          title: 'Confirmar',
          text: '¿Estás seguro de aprobar este préstamo?',
          icon: 'warning',
          buttons: ['Cancelar', 'Sí, aprobar'],
          dangerMode: false,
        })
        .then((willConfirm) => {
          if (willConfirm) {
            axios.post(`/admin/prestamos/libros/${id}/aprobarPrestamoLibro`)
              .then(response => {
                if (response.data.includes('ok')) {
                  this.$swal('Éxito', 'El préstamo ha sido aprobado.', 'success');
                  const prestamo = this.prestamos.find(p => p.id === id);
                    if (prestamo) {
                      prestamo.nombre_estado = 'Aprobado';
                      prestamo.id_estado_solicitud = 2;
                    }
                } else {
                  this.$swal('Error', 'No se pudo aprobar el préstamo.', 'error');
                }
              })
              .catch(error => {
                console.error(error);
                this.$swal('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
              });
          }
        });
      },
      rechazarPrestamo(id) {
        this.$swal({
          title: 'Confirmar',
          text: '¿Estás seguro de rechazar este préstamo?',
          icon: 'warning',
          buttons: ['Cancelar', 'Sí, rechazar'],
          dangerMode: false,
        })
        .then((willConfirm) => {
          if (willConfirm) {
            axios.post(`/admin/prestamos/libros/${id}/rechazarPrestamoLibro`)
              .then(response => {
                if (response.data.includes('ok')) {
                  this.$swal('Éxito', 'El préstamo ha sido rechazado.', 'success');
                  const prestamo = this.prestamos.find(p => p.id === id);
                    if (prestamo) {
                      prestamo.nombre_estado = 'Rechazado';
                      prestamo.id_estado_solicitud = 3;
                    }
                } else {
                  this.$swal('Error', 'No se pudo rechazar el préstamo.', 'error');
                }
              })
              .catch(error => {
                console.error(error);
                this.$swal('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
              });
          }
        });
      },





      // editarLibro(id) {
      //  axios.get(`/admin/libros/editar/${id}`)
      //     .then(res => {
      //       this.editarLibroData = res.data.libros;
      //       this.datosOriginales = JSON.parse(JSON.stringify(res.data.libros));
      //       this.generos = res.data.generos;


      //     })
      //     .catch(() => {
      //       alert('No se pudo cargar el libro.');
      //     });
      // },
      // async eliminarLibro(id) {
      //   if (!confirm('¿Estás seguro de eliminar este libro?')) return;
      //   try {
      //     await axios.delete(`/admin/libros/${id}`);
      //     this.libros = this.libros.filter(l => l.id !== id);
      //     this.$emit('eliminado', id);
      //   } catch (error) {
      //     console.error('Error al eliminar:', error);
      //   }
      // },
      
//     cerrarModal() {
//       this.$emit('cerrar');
//       this.editarLibroData = null;
//     },
//     onFileChange(e) {
//   const file = e.target.files[0];
//   if (file) {
//     this.editarLibroData.img = file.name;
//     alert('Imagen seleccionada: ' + this.editarLibroData.img);
    
//   }
// },

  //   actualizarLibro(){
  //     const cambios= {};
  //     for (const key in this.editarLibroData) {
  //       if (this.editarLibroData[key] !== this.datosOriginales[key]) {
  //         cambios[key] = this.editarLibroData[key];
  //         alert(cambios[key]);
  //       }
  //     }
  //     if (Object.keys(cambios).length === 0) {
  //       Swal.fire({
  //         icon: 'info',
  //         title: 'Sin cambios',
  //         text: 'No se realizaron cambios.',
  //       });
  //       return;
  // }
      
      
  //     axios.put(`/admin/libros/editar/${this.editarLibroData.id}`, cambios)
  //       .then(res => {
  //         if(res.data!='2'){
  //           Swal.fire({
  //             title: "Éxito",
  //             text: "Libro actualizado correctamente.",
  //             icon: "success"
  //           });
  //           this.cerrarModal();
  //           this.libros = res.data;

  //         }else{
  //           Swal.fire({
  //             icon: 'error',
  //             title: 'Error',
  //             text: 'Error al actualizar el libro.'
  //           });
  //         }
          
          
  //       })
  //       .catch(() => {
  //         Swal.fire({
  //             icon: 'error',
  //             title: 'Error',
  //             text: 'Error al actualizar el libro.'
  //           });
  //       });

  //   }

    }
  };
 
  </script>