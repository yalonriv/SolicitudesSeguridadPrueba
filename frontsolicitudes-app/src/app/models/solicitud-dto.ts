export interface SolicitudDTO {
    id: number;
    tipo_estudio: {
      nombre: string;
      precio: number;
    };
    estado: string;
    candidato: {
      nombre: string;
      documento_identidad: string;
    };
  }
  