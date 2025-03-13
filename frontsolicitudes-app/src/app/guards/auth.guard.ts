import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';

export const authGuard: CanActivateFn = (route, state) => {
  const router = inject(Router);

  // Verificar si estamos en un entorno donde window está disponible
  const isBrowser = typeof window !== 'undefined' && typeof localStorage !== 'undefined';
  const isAuthenticated = isBrowser ? !!localStorage.getItem('auth_token') : false;

  if (!isAuthenticated) {
    router.navigate(['/login']); // Redirige al login si no está autenticado
    return false;
  }

  return true; // Permite el acceso si está autenticado
};
