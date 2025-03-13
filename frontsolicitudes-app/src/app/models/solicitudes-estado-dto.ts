/**
 * Interfaz que representa el estado de una solicitud junto con su total asociado.
 */
export interface SolicitudEstadoDTO {

  /**
   * Estado de la solicitud.
   * Ejemplo: "pendiente", "aprobado", "rechazado".
   */
  estado: string;

  /**
   * Número total de solicitudes en este estado.
   */
  total: number;
}
