import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CandidatosService } from '../../services/candidatos.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-candidatos',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule], 
  templateUrl: './candidatos.component.html',
  styleUrls: ['./candidatos.component.css']
})

/**
 * Clase para administración de candidatos
 */
export class CandidatosComponent implements OnInit {
  private fb = inject(FormBuilder);
  private candidatosService = inject(CandidatosService);

  candidatos: any[] = [];
  candidatoForm: FormGroup;
  mostrarFormulario = false;
  editandoId: number | null = null;
  errorMessage: string = '';
  errorDetails: any[] = []; 

  constructor() {
    /**
     * Inicializa el formulario de candidatos con validaciones.
     */
    this.candidatoForm = this.fb.group({
      nombre: ['', Validators.required],
      apellido: ['', Validators.required],
      documento_identidad: ['', Validators.required],
      correo: ['', [Validators.required, Validators.email]],
      telefono: ['', Validators.required]
    });
  }

  /**
   * Hook del ciclo de vida de Angular que se ejecuta al inicializar el componente.
   * Obtiene la lista de candidatos.
   */
  ngOnInit() {
    this.obtenerCandidatos();
  }

  /**
   * Obtiene la lista de candidatos desde el servicio y la asigna a la variable `candidatos`.
   */
  obtenerCandidatos() {
    this.candidatosService.getCandidatos().subscribe({
      next: (data) => this.candidatos = data,
      error: (err) => this.errorMessage = err.message // Captura error
    }, 
  );
    
  }

  /**
   * Muestra u oculta el formulario de candidatos y resetea el formulario.
   */
  toggleFormulario() {
    this.mostrarFormulario = !this.mostrarFormulario;
    this.editandoId = null;
    this.candidatoForm.reset();
  }

  /**
   * Carga los datos del candidato en el formulario para su edición.
   * @param candidato - Objeto con la información del candidato a editar.
   */
  editarCandidato(candidato: any) {
    this.mostrarFormulario = true;
    this.editandoId = candidato.id;

    this.candidatoForm.patchValue({
      nombre: candidato.nombre,
      apellido: candidato.apellido,
      documento_identidad: candidato.documento_identidad,
      correo: candidato.correo,
      telefono: candidato.telefono
    });
  }

  /**
   * Guarda un candidato nuevo o actualiza uno existente según el estado de `editandoId`.
   * Muestra alertas de éxito y recarga la lista de candidatos.
   */
  guardarCandidato() {
    if (this.candidatoForm.invalid) return;

    this.errorMessage = ''; // Limpiar mensaje de error antes de la petición
    const candidatoData = this.candidatoForm.value;

    if (this.editandoId !== null) {
      // 🚀 ACTUALIZAR CANDIDATO
      this.candidatosService.actualizarCandidato(this.editandoId, candidatoData).subscribe({
        next: (response) => this.verificarRespuesta(response),
        error: (err) => this.manejarError(err)
      });

    } else {
      // 🚀 CREAR NUEVO CANDIDATO
      this.candidatosService.crearCandidato(candidatoData).subscribe({
        next: (response) => this.verificarRespuesta(response),
        error: (err) => this.manejarError(err)
      });
    }
  }

  /**
   * Elimina un candidato después de confirmar con el usuario.
   * @param id - Identificador del candidato a eliminar.
   */
  eliminarCandidato(id: number) {
    if (confirm('¿Estás seguro de eliminar este candidato?')) {
      this.candidatosService.eliminarCandidato(id).subscribe({
        next: () => {
          alert('Candidato eliminado correctamente');
          this.obtenerCandidatos(); // Recargar lista
        },
        error: (err) => this.errorMessage = err.message // Captura error
      });
    }
  }

/**
 * Verifica si la respuesta del backend indica un error y lo muestra en pantalla.
 */
  verificarRespuesta(response: any) {
    if (response.errors!=null) {
      this.manejarError({ error: response });
    } else {
      alert('Operación exitosa');
      this.obtenerCandidatos();
      this.toggleFormulario();
    }
  }
/**
 * Maneja los errores de la API y los asigna a la variable `errorMessage`.
 */
  manejarError(err: any) {
    if (err.error) {
      if (err.error.message) {
        this.errorMessage = err.error.message; // Mensaje general
      }
      if (err.error.errors) {
        this.errorMessage;
        Object.keys(err.error.errors).forEach((campo) => {
          this.errorMessage += `${err.error.errors[campo].join(', ')}`;
        });
      }
      alert(this.errorMessage)
    } else {
      this.errorMessage = 'Ocurrió un error inesperado.';
    }
  }
  
}
