import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CandidatosService {
  private apiUrl = 'http://localhost:8000/api/candidatos'; // Ajusta según tu backend
  private http = inject(HttpClient);

  getCandidatos(): Observable<any> {
    return this.http.get(this.apiUrl);
  }

  getCandidato(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/${id}`);
  }

  crearCandidato(data: any): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }

  actualizarCandidato(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, data);
  }

  eliminarCandidato(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
}
