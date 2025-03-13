import { Component, inject } from '@angular/core';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { FormBuilder, ReactiveFormsModule, FormGroup, Validators, FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

/**
 * Componente del Login que permite iniciar sesión.
 */
@Component({
  selector: 'app-login', 
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, FormsModule, RouterModule], 
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  // Inyección de dependencias usando inject()
  private authService = inject(AuthService); // Servicio de autenticación
  private fb: FormBuilder = inject(FormBuilder); // Constructor de formularios
  private router = inject(Router); // Para navegación
  private route = inject(ActivatedRoute); // Para obtener parámetros de la URL

  // Creación del formulario con validaciones
  loginForm: FormGroup = this.fb.group({
    email: ['', [Validators.required, Validators.email]], // Campo obligatorio y formato de correo
    password: ['', Validators.required] // Campo obligatorio
  });

  /**
   * Método para iniciar sesión cuando el usuario envía el formulario
   */
  login() {
    // Si el formulario es inválido, no se ejecuta nada
    if (this.loginForm.invalid) return;

    // Obtiene los valores del formulario
    const { email, password } = this.loginForm.value;

    // Llama al servicio de autenticación para iniciar sesión
    this.authService.login(email, password).subscribe({
      next: (response) => {
        localStorage.setItem('auth_token', response.token); // Guarda el token en el almacenamiento local
        alert('Inicio exitoso'); // Muestra un mensaje de éxito
        this.router.navigate(['/dashboard']); // Redirige al dashboard
      },
      error: () => alert('Credenciales incorrectas') // Muestra un mensaje de error si las credenciales son incorrectas
    });
  }
}
