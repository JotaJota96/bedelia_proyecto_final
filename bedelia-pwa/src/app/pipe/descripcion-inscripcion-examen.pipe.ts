import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'descripcionInscripcionExamen'
})
export class DescripcionInscripcionExamenPipe implements PipeTransform {

  transform(value: number, ...args: unknown[]): string {
    //1 = el estudiante está habilitado para inscribirse, 
    //0 = no es necesario dar el examen, 
    //-1 = no ha ganado el derecho a dar examen
    
    switch (value) {
      case  1: return "";
      case  0: return "Curso ya aprobado";
      case -1: return "No se cumple con los requisitos";
      case -2: return "Ya inscrito";
    }
    return "";
  }

}
