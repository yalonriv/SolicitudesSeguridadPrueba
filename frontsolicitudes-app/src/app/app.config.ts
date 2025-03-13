import { ApplicationConfig, importProvidersFrom  } from '@angular/core';
import { provideRouter, Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login.component';

import { provideClientHydration } from '@angular/platform-browser';
import { provideHttpClient, withFetch, withInterceptors } from '@angular/common/http';
import { authInterceptor } from './services/auth-interceptor.service';
import { routes } from './app.routes';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';



export const appConfig: ApplicationConfig = {
  providers: [provideRouter(routes), 
    provideClientHydration(), provideAnimationsAsync(),
    provideHttpClient(withFetch(),withInterceptors([authInterceptor]))
  ]
};


