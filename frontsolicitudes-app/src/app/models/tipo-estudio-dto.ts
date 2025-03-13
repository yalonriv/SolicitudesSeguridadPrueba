/**
 * Interfaz que representa un tipo de estudio.
 */
export interface TipoEstudioDTO {

  /**
   * Identificador único del tipo de estudio.
   */
  id: number;

  /**
   * Nombre del tipo de estudio.
   * Ejemplo: "Análisis de antecedentes", "Prueba psicológica".
   */
  nombre: string;
}
