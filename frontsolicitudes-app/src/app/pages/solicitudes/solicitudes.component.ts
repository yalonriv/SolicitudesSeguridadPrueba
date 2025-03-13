import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { SolicitudesService } from '../../services/solicitudes.service';
import { CommonModule } from '@angular/common';
import { SolicitudEstadoDTO } from '../../models/solicitudes-estado-dto';
import { FiltroSolicitudDTO } from '../../models/filtro-solicitud-dto';

@Component({
  selector: 'app-solicitudes',
  templateUrl: './solicitudes.component.html',
  styleUrls: ['./solicitudes.component.css'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule], 
})
export class SolicitudesComponent {
  solicitudes: any[] = []; // Lista de solicitudes cargadas desde la API
  candidatos: any[] = []; // Lista de candidatos disponibles
  tiposEstudio: any[] = []; // Lista de tipos de estudio
  estados = ['pendiente', 'en_proceso', 'completada']; // Posibles estados de una solicitud

  // Formularios para crear y editar solicitudes
  solicitudFormCrear: FormGroup;
  solicitudFormEditar: FormGroup;

  // Variables de control para la interfaz
  mostrarFormulario = false;
  modoEdicion = false;
  solicitudEditandoId: number | null = null; // ID de la solicitud en edición
  candidato: string = ''; // Nombre del candidato en edición
  tipoEstudio: string = ''; // Nombre del tipo de estudio en edición
  solicitudesPorEstado: SolicitudEstadoDTO[] = []; // Resumen de solicitudes por estado

  // Controles de filtro
  estadoControl = new FormControl('');
  tipoEstudioControl = new FormControl('');

  constructor(private fb: FormBuilder, private solicitudesService: SolicitudesService) {
    // Formulario para CREAR solicitud
    this.solicitudFormCrear = this.fb.group({
      candidato_id: ['', Validators.required],
      tipo_estudio_id: ['', Validators.required],
      estado: ['', Validators.required],
      fecha_solicitud: [new Date().toISOString().split('T')[0]] // Fecha por defecto (hoy)
    });

    // Formulario para EDITAR solicitud
    this.solicitudFormEditar = this.fb.group({
      estado: ['', Validators.required]
    });
  }

  ngOnInit() {
    // Cargar datos iniciales
    this.cargarSolicitudes();
    this.cargarCandidatos();
    this.cargarTiposEstudio();
    this.cargarSolicitudesPorEstado();

    // 🔥 Escuchar cambios en los filtros y recargar automáticamente
    this.estadoControl.valueChanges.subscribe(() => this.cargarSolicitudes());
    this.tipoEstudioControl.valueChanges.subscribe(() => this.cargarSolicitudes());
  }

  /**
   * Carga la lista de solicitudes desde el servicio, aplicando filtros si existen.
   */
  cargarSolicitudes() {
    const filtro: FiltroSolicitudDTO = {
      estado: this.estadoControl.value || undefined,
      tipo_estudio_id: this.tipoEstudioControl.value ? Number(this.tipoEstudioControl.value) : undefined // 🔥 Convertimos a número
    };
    this.solicitudesService.getSolicitudes(filtro).subscribe({
      next: (data) => this.solicitudes = data,
      error: (err) => console.error('Error al cargar solicitudes:', err)
    });
  }

  /**
   * Carga la lista de candidatos desde el servicio.
   */
  cargarCandidatos() {
    this.solicitudesService.getCandidatos().subscribe({
      next: (data) => this.candidatos = data,
      error: (err) => console.error('Error al cargar candidatos:', err)
    });
  }

  /**
   * Carga la lista de tipos de estudio desde el servicio.
   */
  cargarTiposEstudio() {
    this.solicitudesService.getTiposEstudio().subscribe({
      next: (data) => this.tiposEstudio = data,
      error: (err) => console.error('Error al cargar tipos de estudio:', err)
    });
  }

  /**
   * Habilita el formulario para editar una solicitud específica.
   * @param solicitud Solicitud seleccionada para edición.
   */
  editarSolicitud(solicitud: any) {
    this.resetFormulario();
    this.solicitudEditandoId = solicitud.id;
    this.modoEdicion = true;
    this.mostrarFormulario = true;

    this.candidato = solicitud.candidato.nombre;
    this.tipoEstudio = solicitud.tipo_estudio.nombre;

    this.solicitudFormEditar.patchValue({
      estado: solicitud.estado
    });
  }

  /**
   * Guarda una solicitud, ya sea nueva o editada.
   */
  guardarSolicitud() {
    if (this.modoEdicion) {
      // Modo edición: actualizar solicitud existente
      if (this.solicitudFormEditar.valid && this.solicitudEditandoId) {
        const solicitudData = this.solicitudFormEditar.value;

        this.solicitudesService.actualizarEstadoSolicitud(this.solicitudEditandoId, solicitudData).subscribe({
          next: () => {
            alert('Estado actualizado correctamente');
            this.cargarSolicitudes();
            this.resetFormulario();
          },
          error: (error) => console.error('Error al actualizar la solicitud:', error)
        });
      }
    } else {
      // Modo creación: registrar nueva solicitud
      if (this.solicitudFormCrear.valid) {
        const solicitudData = this.solicitudFormCrear.value;
      
        this.solicitudesService.crearSolicitud(solicitudData).subscribe({
          next: () => {
            alert('Solicitud creada correctamente');
            this.cargarSolicitudes();
            this.resetFormulario();
          },
          error: (error) => console.error('Error al crear la solicitud:', error)
        });
      }
    }
    this.cargarSolicitudesPorEstado();
  }

  /**
   * Reinicia los formularios y oculta el formulario de edición/creación.
   */
  resetFormulario() {
    this.solicitudFormCrear.reset();
    this.solicitudFormEditar.reset();
    this.mostrarFormulario = false;
    this.modoEdicion = false;
    this.solicitudEditandoId = null;
  }

  /**
   * Carga el resumen de solicitudes agrupadas por estado.
   */
  cargarSolicitudesPorEstado() {
    this.solicitudesService.getSolicitudesPorEstado().subscribe({
      next: (data) => {
        this.solicitudesPorEstado = data;
        console.log('📊 Datos de solicitudes por estado:', this.solicitudesPorEstado);
      },
      error: (err) => console.error('🚨 Error al cargar estadísticas:', err)
    });
  }
}
