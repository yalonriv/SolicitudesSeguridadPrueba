import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, defaultIfEmpty, map, tap } from 'rxjs/operators';
import { SolicitudDTO } from '../models/solicitud-dto';
import { TipoEstudioDTO } from '../models/tipo-estudio-dto';
import { CandidatoDTO } from '../models/candidato-dto';
import { SolicitudEstadoDTO } from '../models/solicitudes-estado-dto';
import { FiltroSolicitudDTO } from '../models/filtro-solicitud-dto';
import { HttpParams } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class SolicitudesService {
  private apiUrl = 'http://localhost:8000/api';

  private http = inject(HttpClient);

  getSolicitudes(filtro: FiltroSolicitudDTO): Observable<SolicitudDTO[]> {
    let params = new HttpParams();

  if (filtro.estado) {
    params = params.set('estado', filtro.estado);
  }
  if (filtro.tipo_estudio_id) {
    params = params.set('tipo_estudio_id', filtro.tipo_estudio_id.toString()); // 🔥 Convertimos a string
  }
    return this.http.get<SolicitudDTO[]>(`${this.apiUrl}/solicitudes`, { params }).pipe(
      tap(response => console.log('✅ Respuesta de la API:', response)), // Debugging
      map(response => response.map(solicitud => ({
        id: solicitud.id,
        tipo_estudio: solicitud.tipo_estudio ? {
          nombre: solicitud.tipo_estudio.nombre,
          precio: solicitud.tipo_estudio.precio
        } : { nombre: 'Desconocido', precio: 0.0 },
        estado: solicitud.estado,
        candidato: solicitud.candidato ? {
          nombre: solicitud.candidato?.nombre ?? 'Desconocido',
          documento_identidad: solicitud.candidato?.documento_identidad ?? 'N/A'
        } : { nombre: 'Desconocido', documento_identidad: 'N/A' }
      }))),
      tap(processedData => console.log('🚀 Datos procesados:', processedData)), // Ver la transformación final
      defaultIfEmpty([]), // Si no hay datos, retorna un array vacío
      catchError(error => {
        console.error('🚨 Error en la API:', error);
        return throwError(() => new Error('No se pudieron cargar las solicitudes'));
      })
    );
  }
  
  
  getCandidatos(): Observable<CandidatoDTO[]> {
    return this.http.get<any[]>(`${this.apiUrl}/candidatos`).pipe(
      map(response => response.map(c => ({
        id: c.id,
        nombre: c.nombre,
        documento_identidad: c.documento_identidad
      }) as unknown as CandidatoDTO))
    );
  }

  getTiposEstudio(): Observable<TipoEstudioDTO[]> {
    return this.http.get<any[]>(`${this.apiUrl}/tiposEstudio`).pipe(
      map(response => response.map(t => ({
        id: t.id,
        nombre: t.nombre
      }) as TipoEstudioDTO))
    );
  }

  crearSolicitud(solicitud: any): Observable<any> {
    solicitud.fecha_solicitud = new Date().toISOString();
    return this.http.post<any>(`${this.apiUrl}/solicitudes`, solicitud);
  }

  actualizarEstadoSolicitud(id: number, data: any): Observable<any> {

    return this.http.put(`${this.apiUrl}/solicitudes/${id}`, data);
  }

  getSolicitudesPorEstado(): Observable<SolicitudEstadoDTO[]> {
    return this.http.get<SolicitudEstadoDTO[]>(`${this.apiUrl}/solicitudes-estadisticas`);
  }
  
}
