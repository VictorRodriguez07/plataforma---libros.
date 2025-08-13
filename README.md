# Módulo Biblioteca 

Este repositorio contiene el módulo **Biblioteca** desarrollado para un CRM existente.  
**Importante:** el CRM base no fue desarrollado por mí; este módulo agrega la gestión de libros y préstamos dentro del sistema.

---

## Funcionalidades

### Libros
- **Catálogo de libros:** muestra todos los libros disponibles.  
- **Detalle del libro:** permite ver información detallada de cada libro.  
- **Solicitud de préstamo:** los usuarios pueden solicitar un libro para préstamo.  

- **Gestión de libros (CRUD):**  
  - Crear, leer, actualizar y eliminar libros desde la sección de “Consulta de libros”.

### Préstamos
- **Listado de préstamos:** muestra todos los préstamos activos.  
- **Flujo de vida del préstamo:**
  - Aprobar o rechazar solicitudes de préstamo.  
  - Notificar la entrega de libros.  
  - Solicitar y gestionar extensión de plazo (aprobar o denegar).  
  - Notificar devolución del libro y finalizar el préstamo.

### Notificaciones por correo
- Cuando se realiza una solicitud de préstamo, se envía correo a los encargados para aprobar o rechazar.  
- Cuando se aprueba o rechaza una solicitud, el solicitante recibe un correo de notificación.  
- Cuando se solicita extensión de plazo, el encargado recibe correo para aprobar o denegar, y el solicitante recibe notificación de la respuesta.

---

## Integración
Este módulo fue desarrollado para integrarse en un CRM existente. No funciona de manera independiente y requiere del sistema principal para su ejecución.

---

## Tecnologías
- Php (Backend)
- Laravel (Framework backend)  
- Vue.js (Framework frontend)
- Boostrap (Librería frontend)
- JavaScript
- MySQL (Base de datos)
- MySQL Workbench

---

## Autor
Victor Manuelle Rodriguez Mosquera
