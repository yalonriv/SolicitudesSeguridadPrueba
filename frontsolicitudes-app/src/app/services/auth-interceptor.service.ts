import { HttpInterceptorFn } from '@angular/common/http';

/**
 * Interceptor de autenticación para adjuntar el token de autorización
 * en cada solicitud HTTP.
 */
export const authInterceptor: HttpInterceptorFn = (req, next) => {
  let token = '';

  // Verifica si `localStorage` está disponible en el entorno actual
  if (typeof localStorage !== 'undefined') {
    token = localStorage.getItem('auth_token') || '';
  }

  // Clona la solicitud original y agrega el encabezado de autorización con el token
  const clonedRequest = req.clone({
    setHeaders: {
      Authorization: `Bearer ${token}`
    }
  });

  // Continúa con la solicitud modificada
  return next(clonedRequest);
};
