/**
 * Interfaz para definir los filtros de búsqueda de solicitudes.
 */
export interface FiltroSolicitudDTO {
  
  /**
   * Estado de la solicitud (opcional).
   * Puede ser un valor como "pendiente", "aprobado", "rechazado", etc.
   */
  estado?: string;

  /**
   * Identificador del tipo de estudio (opcional).
   */
  tipo_estudio_id?: number;
}
