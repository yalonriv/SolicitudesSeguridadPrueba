import { inject, Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, defaultIfEmpty, map, tap } from 'rxjs/operators';
import { SolicitudDTO } from '../models/solicitud-dto';
import { TipoEstudioDTO } from '../models/tipo-estudio-dto';
import { CandidatoDTO } from '../models/candidato-dto';
import { SolicitudEstadoDTO } from '../models/solicitudes-estado-dto';
import { FiltroSolicitudDTO } from '../models/filtro-solicitud-dto';

@Injectable({
  providedIn: 'root', // Hace que el servicio esté disponible en toda la aplicación
})
export class SolicitudesService {
  private apiUrl = 'http://localhost:8000/api'; // URL base del backend

  private http = inject(HttpClient); // Inyección del servicio HttpClient

  /**
   * Obtiene una lista de solicitudes con filtros opcionales.
   * @param filtro - Objeto con los filtros aplicados.
   * @returns Observable con un array de solicitudes procesadas.
   */
  getSolicitudes(filtro: FiltroSolicitudDTO): Observable<SolicitudDTO[]> {
    let params = new HttpParams();

    // Agrega filtros a la petición si están definidos
    if (filtro.estado) {
      params = params.set('estado', filtro.estado);
    }
    if (filtro.tipo_estudio_id) {
      params = params.set('tipo_estudio_id', filtro.tipo_estudio_id.toString());
    }

    return this.http.get<SolicitudDTO[]>(`${this.apiUrl}/solicitudes`, { params }).pipe(
      tap(response => console.log('Respuesta de la API:', response)), // Depuración
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
      tap(processedData => console.log('Datos procesados:', processedData)), // Depuración de transformación
      defaultIfEmpty([]), // Devuelve un array vacío si no hay datos
      catchError(error => {
        console.error('Error en la API:', error);
        return throwError(() => new Error('No se pudieron cargar las solicitudes'));
      })
    );
  }

  /**
   * Obtiene la lista de candidatos.
   * @returns Observable con un array de candidatos.
   */
  getCandidatos(): Observable<CandidatoDTO[]> {
    return this.http.get<any[]>(`${this.apiUrl}/candidatos`).pipe(
      map(response => response.map(c => ({
        id: c.id,
        nombre: c.nombre,
        documento_identidad: c.documento_identidad
      }) as CandidatoDTO))
    );
  }

  /**
   * Obtiene la lista de tipos de estudio disponibles.
   * @returns Observable con un array de tipos de estudio.
   */
  getTiposEstudio(): Observable<TipoEstudioDTO[]> {
    return this.http.get<any[]>(`${this.apiUrl}/tiposEstudio`).pipe(
      map(response => response.map(t => ({
        id: t.id,
        nombre: t.nombre
      }) as TipoEstudioDTO))
    );
  }

  /**
   * Crea una nueva solicitud.
   * @param solicitud - Datos de la solicitud a crear.
   * @returns Observable con la respuesta del backend.
   */
  crearSolicitud(solicitud: any): Observable<any> {
    solicitud.fecha_solicitud = new Date().toISOString(); // Se añade la fecha de solicitud actual
    return this.http.post<any>(`${this.apiUrl}/solicitudes`, solicitud);
  }

  /**
   * Actualiza el estado de una solicitud específica.
   * @param id - ID de la solicitud a actualizar.
   * @param data - Datos con el nuevo estado.
   * @returns Observable con la respuesta del backend.
   */
  actualizarEstadoSolicitud(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/solicitudes/${id}`, data);
  }

  /**
   * Obtiene estadísticas de solicitudes por estado.
   * @returns Observable con un array de estadísticas por estado.
   */
  getSolicitudesPorEstado(): Observable<SolicitudEstadoDTO[]> {
    return this.http.get<SolicitudEstadoDTO[]>(`${this.apiUrl}/solicitudes-estadisticas`);
  }
}
