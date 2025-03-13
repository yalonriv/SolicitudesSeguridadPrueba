import { Component } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { SolicitudEstadoDTO } from '../../models/solicitudes-estado-dto';
import { SolicitudesService } from '../../services/solicitudes.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent {
  
  solicitudesPorEstado: SolicitudEstadoDTO[] = [];
  constructor(private router: Router, private solicitudesService: SolicitudesService) {}

  ngOnInit(){
    
    this.cargarSolicitudesPorEstado();
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
