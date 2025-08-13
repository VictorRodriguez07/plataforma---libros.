<template>
    <div class="col-md-12">
   
      <div class="card card-primary card-outline">
        <div class="card-header with-border text-center">
          <h2 class="card-title">Listar libros</h2>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped" id="libros-table">
            <thead>
              <tr>
               
                <th class="text-center">Portada</th>
                <th class="text-center">Título</th>
                <th class="text-center">Autor</th>
                <th class="text-center">Fecha de publicación</th>
                <th class="text-center">Género</th>
                <th class="text-center">Categoría</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="libro in libros" :key="libro.id">
                
                <td class="text-center">
                  <img
                    :src="getImageUrl(libro.img)"
                    alt="Portada"
                    width="50"
                    height="100"
                  />
                </td>
                <td class="text-center">{{ libro.nombre }}</td>
                <td class="text-center">{{ libro.autor }}</td>
                <td class="text-center">{{ libro.fecha_publicacion }}</td>
                <td class="text-center">{{ libro.genero_nombre }}</td>
                <td class="text-center">{{ libro.categoria_nombre }}</td>
                <td class="text-center">{{ libro.cantidad }}</td>
                <td class="text-center">{{ libro.estado_nombre }}</td>
                
                <td class="text-center" style="min-width: 200px;">
                  <button
                    @click="editarLibro(libro.id)"
                    class="btn btn-xs btn-primary"
                  >
                    <i class="fas fa-edit"></i> Editar
                  </button>
                  <button
                    @click="eliminarLibro(libro.id)"
                    class="btn btn-danger btn-xs"
                  >
                    <i class="fas fa-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-if="editarLibroData" class="modal fade show" style="display: block; background: rgba(0, 0, 0, 0.5);" tabindex="-1" role="dialog" aria-modal="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 70vw;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Libro: {{ editarLibroData.nombre }}</h5>
        <button type="button" class="close" @click="cerrarModal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 100vh; overflow-y: auto;">
        <div class="container-fluid">
          <div class="row">

            <div class="col-12 col-md-4 text-center mb-3">
              <label for="portada" class="d-block mb-2">Portada</label>
              <img
                :src="'/storage/' + editarLibroData.img"
                alt="Portada"
                class="rounded mx-auto d-block"
                style="max-width: 100%; height: 330px; max-height: 330px; object-fit: cover;"
              />
              <div class="mt-2">
                <label class="btn btn-outline-secondary btn-sm" for="fileInput">
                  Cambiar Imagen
                  <input id="fileInput" type="file" @change="onFileChange" hidden />
                </label>
              </div>
            </div>

            <div class="col-12 col-md-8">
              <form @submit.prevent="actualizarLibro">
                <div class="form-row">
                  <div class="form-group col-12 col-md-6">
                    <label>Título *</label>
                    <input v-model="editarLibroData.nombre" type="text" class="form-control" required />
                  </div>
                  <div class="form-group col-12 col-md-6">
                    <label>Autor *</label>
                    <input v-model="editarLibroData.autor" type="text" class="form-control" required />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-12 col-md-6">
                    <label>Fecha Publicación *</label>
                    <input v-model="editarLibroData.fecha_publicacion" type="date" class="form-control" required />
                  </div>
                  <div class="form-group col-12 col-md-6">
                        <label>Genero *</label>
                        
                        <select v-model="editarLibroData.id_genero" class="form-control" :disabled="generos.length === 0" >
                          <option value="" disabled>Seleccione un género</option>
                          <option v-for="gen in generos" :key="gen.id" :value="gen.id">{{ gen.nombre }}</option>
                        </select>

                      </div>
                </div>
                <div class="form-row">
                    
                  <div class="form-group col-12">
                    <label>Sinopsis *</label>
                    <textarea v-model="editarLibroData.sinopsis" class="form-control" rows="4" required></textarea>
                  </div>
                </div>
                <div class="text-center mt-4">
                  <button type="submit" class="btn btn-success mr-2">Guardar Cambios</button>
                  <button type="button" @click="cerrarModal" class="btn btn-secondary">Cancelar</button>
                </div>
              </form>
            </div>

          </div>
        </div>
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
    name: 'ListarLibros',
    props: {
      listar_libros: {
        type: Array,
        
      }
      
    },
    data() {
      return {
        libros: [...this.listar_libros],
        editarLibroData: null,
        datosOriginales: null,
        generos: null,
      };
    },
    methods: {

      //método para obtener la url de la imágen del libro
      getImageUrl(path) {
        return `/storage/${path}`;
      },

      //método para obtener los datos necesarios del libro seleccionado para ser editado.
      editarLibro(id) {
       axios.get(`/admin/libros/editar/${id}`)
          .then(res => {
            this.editarLibroData = res.data.libros;
            this.datosOriginales = JSON.parse(JSON.stringify(res.data.libros));
            this.generos = res.data.generos;


          })
          .catch(() => {
            alert('No se pudo cargar el libro.');
          });
      },

      //método asincrónico para eliminar un libro
      async eliminarLibro(id) {
        if (!confirm('¿Estás seguro de eliminar este libro?')) return;
        try {
          await axios.delete(`/admin/libros/${id}`);
          this.libros = this.libros.filter(l => l.id !== id);
          this.$emit('eliminado', id);
        } catch (error) {
          console.error('Error al eliminar:', error);
        }
      },
      
      //cerrar la modal de editar libro
    cerrarModal() {
      this.$emit('cerrar');
      this.editarLibroData = null;
    },

    //méotodo emcargado de cambiar la foto en la modal de editar libro al cargar la foto.
    onFileChange(e) {
      const file = e.target.files[0];
      if (file) {
        this.editarLibroData.img = file.name;
        alert('Imagen seleccionada: ' + this.editarLibroData.img);
        
      }
    },

      //método encargado en enviar la petición al backend, por medio de Axios, al backend, para actualizar el libro seleccionado.
    actualizarLibro(){
      const cambios= {};
      for (const key in this.editarLibroData) {
        if (this.editarLibroData[key] !== this.datosOriginales[key]) {
          cambios[key] = this.editarLibroData[key];
          alert(cambios[key]);
        }
      }
      if (Object.keys(cambios).length === 0) {
        Swal.fire({
          icon: 'info',
          title: 'Sin cambios',
          text: 'No se realizaron cambios.',
        });
        return;
  }
      
      
      axios.put(`/admin/libros/editar/${this.editarLibroData.id}`, cambios)
        .then(res => {
          if(res.data!='2'){
            Swal.fire({
              title: "Éxito",
              text: "Libro actualizado correctamente.",
              icon: "success"
            });
            this.cerrarModal();
            this.libros = res.data;

          }else{
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error al actualizar el libro.'
            });
          }
          
          
        })
        .catch(() => {
          Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error al actualizar el libro.'
            });
        });

    }

    }
  };
 
  </script>
  