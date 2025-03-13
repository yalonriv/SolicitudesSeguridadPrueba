import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';

export const authGuard: CanActivateFn = (route, state) => {
  const router = inject(Router);
  const isAuthenticated = !!localStorage.getItem('auth_token'); //Verifica si hay token

  if (!isAuthenticated) {
    router.navigate(['/login']); //Redirige al login si no está autenticado
    return false;
  }

  return true; // Permite el acceso si está autenticado
};
