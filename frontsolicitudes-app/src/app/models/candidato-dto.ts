/**
 * Interfaz que representa la estructura de un candidato.
 */
export interface CandidatoDTO {
  
  /**
   * Identificador único del candidato.
   */
  id: number;

  /**
   * Nombre completo del candidato.
   */
  nombre: string;

  /**
   * Documento de identidad del candidato.
   */
  documento_identidad: string;
}
