import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root' // Permite que el servicio esté disponible en toda la aplicación
})
export class CandidatosService {
  private apiUrl = 'http://localhost:8000/api/candidatos'; // URL base del endpoint de candidatos
  private http = inject(HttpClient); // Inyección del servicio HttpClient

  /**
   * Obtiene la lista de todos los candidatos.
   * @returns Observable con un array de candidatos.
   */
  getCandidatos(): Observable<any> {
    return this.http.get(this.apiUrl);
  }

  /**
   * Obtiene los detalles de un candidato específico.
   * @param id - ID del candidato.
   * @returns Observable con la información del candidato.
   */
  getCandidato(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/${id}`);
  }

  /**
   * Crea un nuevo candidato.
   * @param data - Datos del candidato a registrar.
   * @returns Observable con la respuesta del backend.
   */
  crearCandidato(data: any): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }

  /**
   * Actualiza la información de un candidato existente.
   * @param id - ID del candidato a actualizar.
   * @param data - Datos actualizados del candidato.
   * @returns Observable con la respuesta del backend.
   */
  actualizarCandidato(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, data);
  }

  /**
   * Elimina un candidato del sistema.
   * @param id - ID del candidato a eliminar.
   * @returns Observable con la respuesta del backend.
   */
  eliminarCandidato(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
}
