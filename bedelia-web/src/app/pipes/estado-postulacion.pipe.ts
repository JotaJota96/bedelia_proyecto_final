import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'estadoPostulacion'
})
export class EstadoPostulacionPipe implements PipeTransform {

  transform(value: unknown, ...args: unknown[]): unknown {
      // A = Aceptada, 
      // R = Rechazada, 
      // N = NotificacionEnviada, 
      // null = ninguna de las anteriores

      switch (value) {
        case 'A': return "Aceptada";
        case 'R': return "Rechazada";
        case 'N': return "Notificada";
      }
      return "Sin revisar";
    }
  
}
