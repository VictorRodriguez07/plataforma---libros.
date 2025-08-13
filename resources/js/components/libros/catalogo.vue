<template>

   <div class="catalogo-container pt-4">
       

  <div class="catalogo-header text-center mb-5">
    <h2>
      <i class="fas fa-book-open text-primary"></i> Catálogo de libros
    </h2>
    <p class="catalogo-desc">Descubre tu próxima lectura favorita.</p>
  </div>

  <div class="filtros-card mb-5">
    <div class="row g-3 align-items-center">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" v-model="filtro.nombre" class="form-control" placeholder="Buscar por nombre" @keyup="buscarPorNombre">
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
          <select v-model="filtro.id_genero" class="form-control" @change="buscarPorGenero">
            <option value="">Todos los géneros</option>
            <option v-for="g in generos" :key="g.id" :value="g.id">{{ g.nombre }}</option>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
          <select v-model="filtro.disponibilidad" class="form-control" @change="buscarPorDisponibilidad">
            <option value="">Todos</option>
            <option value="disponible">Disponible</option>
            <option value="no_disponible">No disponible</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="libro-grid">
    <div v-for="libro in libros" :key="libro.id" class="libro-card" @click="mostrarDetalle(libro.id)">
      <div class="libro-cover" :style="{ backgroundImage: 'url(' + (libro.img ? '/storage/' + libro.img : 'https://via.placeholder.com/180x220?text=Sin+Imagen') + ')'}">
        <div class="badge-stock" :class="{ 'no-stock': libro.cantidad < 1 || libro.id_estado==7 }">
          <i :class="(libro.cantidad === 0 || libro.id_estado === 7) ? 'fas fa-times-circle' : 'fas fa-check-circle'"></i>
          {{ (libro.cantidad === 0 || libro.id_estado === 7) ? 'No disponible' : 'Disponible' }}
        </div>
        <div class="cover-overlay">
          <strong>Sinopsis:</strong> <br>
          {{ libro.sinopsis }}
        </div>
      </div>
      <div class="card-body">
        <h5 class="card-title" :title="libro.nombre">{{ libro.nombre }}</h5>
        <div class="card-author">por {{ libro.autor }}</div>
      </div>
    </div>
    <div v-if="!libros.length" class="alert alert-info text-center d-flex justify-content-center align-items-center">
  <i class="fas fa-info-circle me-2"></i> No hay libros en el catálogo.
</div>


  </div>

  <button v-if="flag_ver_mas==true" @click="verMas" class="btn btn-primary btn-lg mt-4 d-block mx-auto">
    <i class="fas fa-arrow-down"></i> Ver más libros
  </button>


  
      <div v-if="flag_detalle_libro" class="modal fade show" style="display:block; background:rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered custom-modal">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ detalleLibro.nombre }}</h5>
              <button type="button" class="close" @click="cerrarModal">&times;</button>
            </div>
            <div class="modal-body">
              <img
                :src="'/storage/' + detalleLibro.img"
                v-if="detalleLibro.img"
                class="detalle-imagen"
              >
              <p><strong>Autor:</strong> {{ detalleLibro.autor }}</p>
              <p><strong>Sinopsis:</strong> {{ detalleLibro.sinopsis }}</p>
              <p><strong>Género:</strong> {{ detalleLibro.genero_nombre }}</p>
              <p><strong>Categoría:</strong> {{ detalleLibro.categoria_nombre }}</p>
              <p><strong>Disponibilidad:</strong>
                <span v-if="detalleLibro.cantidad > 0 && detalleLibro.id_estado==6">Disponible</span>
                <span v-else>No disponible</span>
              </p>
            </div>
            <div class="modal-footer items-center justify-content-center">
              <button class="btn btn-success" :disabled="detalleLibro.cantidad==0  || detalleLibro.id_estado==7 " @click="pedirLibro">
              {{ detalleLibro.cantidad > 0 && detalleLibro.id_estado===6? 'Pedir libro' : 'No disponible' }}
            </button>
              <button class="btn btn-secondary" @click="cerrarModal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

<div v-if="flag_pedir_libro" class="modal fade show" style="display:block; background:rgba(0,0,0,0.5); border-radius: 20px;" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered custom-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{detalleLibro.nombre}}</h5>
        <button type="button" class="close" @click="cerrarModalPedir">&times;</button>
      </div>
      <form @submit.prevent="crearPrestamo">
  <div class="modal-body">
    <img
      :src="'/storage/' + detalleLibro.img"
      v-if="detalleLibro.img"
      class="detalle-imagen"
    >

    <div class="form-group mt-3">
      <label>¿Estás en TQI Cali? *</label><br>
      <input type="radio" name="tqi" value="si" v-model="enTqi" @click="ocultarDireccion"> Sí
      <input type="radio" name="tqi" value="no" v-model="enTqi" @click="mostrarDireccion"> No

      <small v-if="errores.enTqi" class="text-danger">{{ errores.enTqi }}</small>

    </div>

    <div v-if="flag_tqi_cali==false" class="form-group">
      <label for="direccion">Dirección de residencia. *</label>
      <input type="text" v-model="direccion" class="form-control" id="direccion" placeholder="Ingresa tu dirección">

      <small v-if="errores.direccion" class="text-danger">{{ errores.direccion }}</small>
    </div>

    <div class="form-group">
      <label for="dias">Cantidad de días que requiere el libro. *</label>
      <input type="number"  v-model="dias" min="1" class="form-control" id="dias" placeholder="Ej: 5">
      <small v-if="errores.dias" class="text-danger">{{ errores.dias }}</small>
    </div>
  </div>

  <div  class="modal-footer items-center justify-content-center">
    <button type="submit" class="btn btn-success">
      Confirmar pedido
    </button>
    <button  type="button" class="btn btn-secondary" @click="cerrarModalPedir">Cerrar</button>
  </div>
</form>

    </div>
  </div>
</div>


    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import Swal from 'sweetalert2';
  export default {
    props: {
      librosIniciales: {
        type: Array,
        required: true
      },
      generosIniciales: {
        type: Array,
        required: true
      },
      categoriasIniciales: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        libros: this.librosIniciales,
        generos: this.generosIniciales,
        categorias: this.categoriasIniciales,
        detalleLibro: {},
        pedirLibros: {},
        flag_detalle_libro: null,
        flag_pedir_libro: null,
        flag_tqi_cali:true,
        flag_ver_mas: true,
        librosCargar: 0,
        enTqi: "",   
        direccion: "", 
        dias: "",
        errores: {
          enTqi: '',
          direccion: '',
          dias: ''
        },
        filtro: {
        nombre: '',
        id_genero: '',
        id_categoria: '',
        disponibilidad: ''
        },

      };
    },
    methods: {

    //métdo encargado de realizar petición para obtener los datos detallado del libro con el ID dado como parámetro.
      mostrarDetalle(id) {
  axios.get(`/admin/detalle/${id}`)
    .then(res => {
      this.detalleLibro = res.data;
      this.flag_detalle_libro = true;
    })
    .catch(() => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo cargar el detalle del libro.',
      });
    });
},

      cerrarModal() {
        this.flag_detalle_libro = null;
        
      },
      cerrarModalPedir(){
        this.flag_pedir_libro = null;
      },
      pedirLibro() {
  if (this.detalleLibro.cantidad > 0) {
    this.flag_detalle_libro = null; 
    this.flag_pedir_libro = true;
  } else {
    Swal.fire({
      icon: 'warning',
      title: 'No disponible',
      text: 'El libro no está disponible actualmente.',
    });
  }
},

// método encargado de realizar petición al backend para registrar la solicitud de préstamo del libro seleccionado.
  crearPrestamo() {
    let esValido = true;

    if (this.enTqi !== "si" && this.enTqi !== "no") {
      this.errores.enTqi = "Por favor selecciona si estás en TQI Cali.";
      esValido = false;
    }

    if (this.enTqi === "no" && this.direccion === "") {
      this.errores.direccion = "Por favor ingresa tu dirección.";
      esValido = false;
    }

    if (!this.dias || this.dias < 1) {
      this.errores.dias = "Por favor ingresa una cantidad válida de días (mínimo 1 día).";
      esValido = false;
    }

    if (!esValido) {
      Swal.fire({
        icon: 'error',
        title: 'Campos incompletos',
        text: 'Por favor revisa los errores en el formulario.',
      });
      return;
    }

    this.errores.enTqi = '';
    this.errores.direccion = '';
    this.errores.dias = '';

    const prestamoData = {
      libro_id: this.detalleLibro.id,
      dias: this.dias,
      direccion: this.direccion,
      enTqi: this.enTqi
    };

    Swal.fire({
      title: 'Creando préstamo...',
      text: 'Por favor espera mientras se procesa tu solicitud.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    axios.post('/admin/solicitudPrestamoLibro', prestamoData)
      .then(res => {

        if (res.data.includes("ok") ) {
          Swal.close();
          Swal.fire({
            icon: 'success',
            title: '¡Préstamo creado!',
            text: 'Tu solicitud se ha registrado con éxito.',
          });
          this.flag_pedir_libro = null;
          this.flag_detalle_libro = null;
          this.cargarLibros();

        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al registrar el préstamo.',
          });
        }
      })
      .catch(() => {
        Swal.close();
        Swal.fire({
          icon: 'error',
          title: 'Error del servidor',
          text: 'No se pudo conectar con el servidor.',
        });
      });
  },

      //méotodo encargado de ocultar el campo de dirección en el formulario de solicitar el libro.
      ocultarDireccion(){
        this.flag_tqi_cali = true;
      },
       //méotodo encargado de mostrar el campo de dirección en el formulario de solicitar el libro.
      mostrarDireccion(){
        this.flag_tqi_cali = false;
       
      },

      //metodo encargado de cargar libros. se le solicitia al backend que nos retorne todos los libros. los libros retornados, mediante la data
      //se pegan a la prop libros
      cargarLibros() {
        axios.get('/admin/libros/catalogo/cargar')
          .then(res => {
            this.libros = res.data;
          })
          .catch(() => {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'No se pudieron cargar los libros.',
            });
          });
      },

      //método para buscar libro por medio del nombre.
      buscarPorNombre() {
        if (this.filtro.nombre.trim() === '') {
          this.libros = this.librosIniciales; 
          this.flag_ver_mas = true;
        } else {
          this.flag_ver_mas = false;
          axios.get(`/admin/libros/catalogo/buscarNombre/${this.filtro.nombre}`)
            .then(res => {
              this.libros = res.data;
            })
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda.',
              });
            });
        }
      },

      //método para buscar libro por medio de la categoría.
      buscarPorCategoria() {
        if (this.filtro.id_categoria === '') {
          this.libros = this.librosIniciales; 
          this.flag_ver_mas = true;
        } else {
          this.flag_ver_mas = false;
          axios.get(`/admin/libros/catalogo/buscarCategoria/${this.filtro.id_categoria}`)
            .then(res => {
              this.libros = res.data;
            })
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda por categoría.',
              });
            });
        }
      },

      //método para buscar libro por medio del género.
      buscarPorGenero() {
        if (this.filtro.id_genero === '') {
          this.libros = this.librosIniciales; 
          this.flag_ver_mas = true;
        } else {
          this.flag_ver_mas = false;
          axios.get(`/admin/libros/catalogo/buscarGenero/${this.filtro.id_genero}`)
            .then(res => {
              this.libros = res.data;
            })
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda por género.',
              });
            });
        }
      },

      //método para buscar libro por medio de la disponibilidad.
      buscarPorDisponibilidad() {
        if (this.filtro.disponibilidad === '') {
          this.libros = this.librosIniciales; 
          this.flag_ver_mas = true;
        } else {
          this.flag_ver_mas = false;
          let id_estado = (this.filtro.disponibilidad === 'disponible') ? 6 : 7;

          axios.get(`/admin/libros/catalogo/buscarDisponibilidad/${id_estado}`)
            .then(res => {
              this.libros = res.data;
            })
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda por disponibilidad.',
              });
            });
        }
      },

      //método encargado de cargar los libros al pulsar en el botón "Ver más". se le solicita al backend desde cual ID retornar los libros
      //y su respuesta se concatena a la prop libros.
      verMas() {
        this.librosCargar = this.libros[this.libros.length - 1].id;

        if (this.librosCargar) {
          axios.get(`/admin/libros/catalogo/verMas/${this.librosCargar}`)
            .then(res => {
              if (res.data.length > 0) {
                this.libros = this.libros.concat(res.data);
              } else {
                Swal.fire({
                  icon: 'info',
                  title: 'Fin del catálogo',
                  text: 'No hay más libros para mostrar.',
                });
              }
            })
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar más libros.',
              });
            });
        }
      }

  }
  };
  </script>
  
<style scoped>

.catalogo-container {
  max-width: 80%;
  max-height: 80%;
  margin: 0 auto;

  padding: 4.5rem;
  background: #f9fafc;
  border-radius: 20px;
  box-shadow: 0 2px 24px rgba(0,0,0,0.07);
}
.catalogo-header h2 {
  font-weight: 700;
  font-size: 2.2rem;
  letter-spacing: 1px;
  margin-bottom: 0.5rem;
}
.catalogo-desc {
  color: #666;
  font-size: 1.1rem;
  margin-bottom: 2rem;
}
.filtros-card {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
  padding: 1.5rem 1rem;
}
.input-group-text {
  background: #f2f2f7;
  border: none;
  color: #007bff;
  font-size: 1.1rem;
  margin-right: 12px;
}
.form-control {
  border-radius: 10px !important;
  box-shadow: 0 2px 6px rgba(0,0,0,0.03);
  border: 1px solid #e0e0e0;
}
.libro-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 2rem;
}
.libro-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: transform 0.25s, box-shadow 0.25s;
  position: relative;
  cursor: pointer;
  display: flex;
  flex-direction: column;
}
.libro-card:hover {
  transform: translateY(-8px) scale(1.00);
  box-shadow: 0 8px 32px rgba(0,0,0,0.13);
}
.libro-cover {
  width: 100%;
  padding-bottom: 135%;
  background-size: cover;
  background-position: center;
  position: relative;
}
.badge-stock {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 4px 10px;
  font-size: 13px;
  color: #fff;
  background: #28a745;
  border-radius: 6px;
  text-transform: uppercase;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.09);
}
.badge-stock.no-stock {
  background: #d9534f;
}
.cover-overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(30,30,30,0.82);
  color: #fff;
  opacity: 0;
  padding: 18px 14px;
  font-size: 15px;
  overflow-y: auto;
  transition: opacity 0.35s;
  border-radius: 0 0 16px 16px;
  z-index: 2;
}
.libro-card:hover .cover-overlay {
  opacity: 1;
}
.card-body {
  padding: 16px 14px 14px 14px;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.card-title {
  font-size: 19px;
  font-weight: 700;
  margin-bottom: 2px;
  color: #222;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
.card-author {
  font-size: 14px;
  color: #888;
  margin-bottom: 8px;
  font-style: italic;
}
.btn-primary.btn-lg {
  padding: 0.75rem 2.5rem;
  font-size: 1.15rem;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);  
  font-weight: 600;
  letter-spacing: 1px;
  transition: background 0.2s;
}
.btn-primary.btn-lg:hover {
  background: #0056b3;
}
@media (max-width: 768px) {
  .catalogo-container { padding: 0.7rem; }
  .libro-grid { gap: 1rem; }
  .filtros-card { padding: 1rem 0.5rem; }
}


.custom-modal {
  max-width: 600px;  
  width: 100%;        
}


.custom-modal .modal-body {
  max-height: 70vh;   
  overflow-y: auto;   
  padding: 1.5rem;    
}


.custom-modal .detalle-imagen {
  max-width: 100%;    
  max-height: 40vh;  
  height: auto;      
  display: block;
  margin: 0 auto 1rem;
  border-radius: 10px;
}

.centrado {
  text-align: center;
}
.form-control {
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }
  .container {
  max-width: 1000px; 
  margin: 0 auto;
}

</style>
  