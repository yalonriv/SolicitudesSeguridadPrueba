import { Component, inject } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.css'
})
export class NavbarComponent {
 private router = inject(Router);
 constructor( private authService: AuthService) {}
 logout() {
  this.authService.logout().subscribe({
    next: () => {
      localStorage.removeItem('auth_token'); // 🔥 Eliminar el token
      this.router.navigate(['/login']); // 🔥 Redirigir al login
    },
    error: () => {
      localStorage.removeItem('auth_token'); // 🔥 Eliminar el token aunque falle
      this.router.navigate(['/login']); // 🔥 Redirigir al login
    }
  });
}
}
