import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CandidatosService } from '../../services/candidatos.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-candidatos',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule], 
  templateUrl: './candidatos.component.html',
  styleUrls: ['./candidatos.component.css']
})
export class CandidatosComponent implements OnInit {
  private fb = inject(FormBuilder);
  private candidatosService = inject(CandidatosService);

  candidatos: any[] = [];
  candidatoForm: FormGroup;
  mostrarFormulario = false;
  editandoId: number | null = null;

  constructor() {
    this.candidatoForm = this.fb.group({
      nombre: ['', Validators.required],
      apellido: ['', Validators.required],
      documento_identidad: ['', Validators.required],
      correo: ['', [Validators.required, Validators.email]],
      telefono: ['', Validators.required]
    });
  }

  ngOnInit() {
    this.obtenerCandidatos();
  }

  obtenerCandidatos() {
    this.candidatosService.getCandidatos().subscribe({
      next: (data) => this.candidatos = data,
      error: (err) => console.error('Error al obtener candidatos', err)
    });
  }

  toggleFormulario() {
    this.mostrarFormulario = !this.mostrarFormulario;
    this.editandoId = null;
    this.candidatoForm.reset();
  }

  editarCandidato(candidato: any) {
    this.mostrarFormulario = true;
    this.editandoId = candidato.id;

    this.candidatoForm.patchValue({
      nombre: candidato.nombre,
      apellido: candidato.apellido,
      documento_identidad: candidato.documento_identidad,
      correo: candidato.correo,
      telefono: candidato.telefono
    });
  }

  guardarCandidato() {
    if (this.candidatoForm.invalid) return;

    const candidatoData = this.candidatoForm.value;

    if (this.editandoId !== null) {
      // 🚀 ACTUALIZAR CANDIDATO
      this.candidatosService.actualizarCandidato(this.editandoId, candidatoData).subscribe({
        next: () => {
          alert('Candidato actualizado correctamente');
          this.obtenerCandidatos();
          this.toggleFormulario();
        },
        error: (err) => console.error('Error al actualizar candidato', err)
      });

    } else {
      // 🚀 CREAR NUEVO CANDIDATO
      this.candidatosService.crearCandidato(candidatoData).subscribe({
        next: () => {
          alert('Candidato creado correctamente');
          this.obtenerCandidatos();
          this.toggleFormulario();
        },
        error: (err) => console.error('Error al crear candidato', err)
      });
    }
  }

  eliminarCandidato(id: number) {
    if (confirm('¿Estás seguro de eliminar este candidato?')) {
      this.candidatosService.eliminarCandidato(id).subscribe({
        next: () => {
          alert('Candidato eliminado correctamente');
          this.obtenerCandidatos(); // Recargar lista
        },
        error: (err) => console.error('Error al eliminar candidato', err)
      });
    }
  }
}
