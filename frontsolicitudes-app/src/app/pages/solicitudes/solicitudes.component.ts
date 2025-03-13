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
  solicitudes: any[] = [];
  candidatos: any[] = [];
  tiposEstudio: any[] = [];
  estados = ['pendiente', 'en_proceso', 'completada'];

  solicitudFormCrear: FormGroup;
  solicitudFormEditar: FormGroup;

  mostrarFormulario = false;
  modoEdicion = false;
  solicitudEditandoId: number | null = null;
  candidato: string = '';
  tipoEstudio: string = '';
  solicitudesPorEstado: SolicitudEstadoDTO[] = [];
  // 🔥 Filtros con FormControl
  estadoControl = new FormControl('');
  tipoEstudioControl = new FormControl('');

  constructor(private fb: FormBuilder, private solicitudesService: SolicitudesService) {
    // Formulario para CREAR solicitud
    this.solicitudFormCrear = this.fb.group({
      candidato_id: ['', Validators.required],
      tipo_estudio_id: ['', Validators.required],
      estado: ['', Validators.required],
      fecha_solicitud: [new Date().toISOString().split('T')[0]]
    });

    // Formulario para EDITAR solicitud
    this.solicitudFormEditar = this.fb.group({
      estado: ['', Validators.required]
    });
  }

  ngOnInit() {
    this.cargarSolicitudes();
    this.cargarCandidatos();
    this.cargarTiposEstudio();
    this.cargarSolicitudesPorEstado();

     // 🔥 Escuchar cambios en los filtros y aplicar automáticamente
     this.estadoControl.valueChanges.subscribe(() => this.cargarSolicitudes());
     this.tipoEstudioControl.valueChanges.subscribe(() => this.cargarSolicitudes());
    
  }

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

  cargarCandidatos() {
    this.solicitudesService.getCandidatos().subscribe({
      next: (data) => this.candidatos = data,
      error: (err) => console.error('Error al cargar candidatos:', err)
    });
  }

  cargarTiposEstudio() {
    this.solicitudesService.getTiposEstudio().subscribe({
      next: (data) => this.tiposEstudio = data,
      error: (err) => console.error('Error al cargar tipos de estudio:', err)
    });
  }

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

  guardarSolicitud() {
    if (this.modoEdicion) {
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

  resetFormulario() {
    this.solicitudFormCrear.reset();
    this.solicitudFormEditar.reset();
    this.mostrarFormulario = false;
    this.modoEdicion = false;
    this.solicitudEditandoId = null;
  }

  cargarSolicitudesPorEstado() {
    this.solicitudesService.getSolicitudesPorEstado().subscribe({
      next: (data) => {
        this.solicitudesPorEstado = data;
        console.log(' Datos de solicitudes por estado:', this.solicitudesPorEstado);
      },
      error: (err) => console.error('🚨 Error al cargar estadísticas:', err)
    });
  }
}
