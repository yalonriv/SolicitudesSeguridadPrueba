import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

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
    return this.http.get(this.apiUrl).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Obtiene los detalles de un candidato específico.
   * @param id - ID del candidato.
   * @returns Observable con la información del candidato.
   */
  getCandidato(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/${id}`).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Crea un nuevo candidato.
   * @param data - Datos del candidato a registrar.
   * @returns Observable con la respuesta del backend.
   */
  crearCandidato(data: any): Observable<any> {
    return this.http.post(this.apiUrl, data).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Actualiza la información de un candidato existente.
   * @param id - ID del candidato a actualizar.
   * @param data - Datos actualizados del candidato.
   * @returns Observable con la respuesta del backend.
   */
  actualizarCandidato(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, data).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Elimina un candidato del sistema.
   * @param id - ID del candidato a eliminar.
   * @returns Observable con la respuesta del backend.
   */
  eliminarCandidato(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Manejo de errores para todas las solicitudes HTTP.
   * @param error - Error recibido de la API.
   * @returns Observable con un error procesado.
   */
  private handleError(error: HttpErrorResponse) {
    let errorMessage = 'Ocurrió un error inesperado.';

    if (error.error instanceof ErrorEvent) {
      // Error del lado del cliente
      errorMessage = `Error del cliente: ${error.error.message}`;
    } else {
      // Error del backend
      if (error.status === 0) {
        errorMessage = 'No se pudo conectar con el servidor. Verifica tu conexión.';
      } else if (error.status === 400) {
        errorMessage = `Solicitud incorrecta: ${error.error.message}`;
      } else if (error.status === 401) {
        errorMessage = 'No autorizado. Inicia sesión nuevamente.';
      } else if (error.status === 403) {
        errorMessage = 'Acceso denegado.';
      } else if (error.status === 404) {
        errorMessage = 'Recurso no encontrado.';
      } else if (error.status === 422) {
        errorMessage = 'Errores de validación en los datos enviados.';
      } else if (error.status >= 500) {
        errorMessage = 'Error en el servidor. Inténtalo más tarde.';
      }
    }

    return throwError(() => new Error(errorMessage));
  }
}
