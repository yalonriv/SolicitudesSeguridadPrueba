import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { CandidatosComponent } from './pages/candidatos/candidatos.component';
import { SolicitudesComponent } from './pages/solicitudes/solicitudes.component';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [
    { path: '', component: LoginComponent }, // Página principal
    { path: 'candidatos', component: CandidatosComponent, canActivate: [authGuard]},
    { path: 'solicitudes', component: SolicitudesComponent, canActivate: [authGuard] },
    { path: 'dashboard', component: DashboardComponent, canActivate: [authGuard] },
    { path: '**', redirectTo: '' } 
    
];

