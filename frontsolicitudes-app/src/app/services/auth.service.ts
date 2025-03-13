import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root', // Provee el servicio a toda la aplicación
})
export class AuthService {
  private apiUrl = 'http://localhost:8000/api'; // URL base de la API, ajusta según el backend
  private http = inject(HttpClient); // Inyección del servicio HttpClient

  /**
   * Inicia sesión en el sistema.
   * @param email - Correo electrónico del usuario.
   * @param password - Contraseña del usuario.
   * @returns Observable con la respuesta del backend (incluye token de autenticación).
   */
  login(email: string, password: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, { email, password });
  }

  /**
   * Cierra la sesión del usuario.
   * @returns Observable con la respuesta del backend.
   */
  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/logout`, {});
  }

  /**
   * Obtiene la información del usuario autenticado.
   * @returns Observable con los datos del usuario.
   */
  getUser(): Observable<any> {
    return this.http.get(`${this.apiUrl}/user`);
  }
}
