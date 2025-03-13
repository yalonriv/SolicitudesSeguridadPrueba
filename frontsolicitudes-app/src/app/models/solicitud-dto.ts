/**
 * Interfaz que representa una solicitud de estudio.
 */
export interface SolicitudDTO {

  /**
   * Identificador único de la solicitud.
   */
  id: number;

  /**
   * Información sobre el tipo de estudio solicitado.
   */
  tipo_estudio: {

    /**
     * Nombre del tipo de estudio.
     */
    nombre: string;

    /**
     * Precio del estudio.
     */
    precio: number;
  };

  /**
   * Estado actual de la solicitud.
   * Puede ser valores como "pendiente", "aprobado" o "rechazado".
   */
  estado: string;

  /**
   * Datos del candidato que realizó la solicitud.
   */
  candidato: {

    /**
     * Nombre del candidato.
     */
    nombre: string;

    /**
     * Documento de identidad del candidato.
     */
    documento_identidad: string;
  };
}
