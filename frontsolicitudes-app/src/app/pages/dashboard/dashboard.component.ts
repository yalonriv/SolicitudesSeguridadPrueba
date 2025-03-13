import { Component } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { SolicitudEstadoDTO } from '../../models/solicitudes-estado-dto';
import { SolicitudesService } from '../../services/solicitudes.service';

/**
 * Componente del Dashboard que muestra el estado de las solicitudes.
 * 
 * - Carga estadísticas de solicitudes agrupadas por estado.
 */
@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent {
  
  /** Lista de solicitudes agrupadas por estado */
  solicitudesPorEstado: SolicitudEstadoDTO[] = [];

  /**
   * Constructor del componente.
   * @param router - Servicio de navegación.
   * @param solicitudesService - Servicio para obtener datos de solicitudes.
   */
  constructor(private router: Router, private solicitudesService: SolicitudesService) {}

  /**
   * Método de inicialización del componente.
   * Se ejecuta cuando el componente ha sido cargado.
   */
  ngOnInit(){
    this.cargarSolicitudesPorEstado();
  }

  /**
   * Carga las estadísticas de solicitudes agrupadas por estado desde el servicio.
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
