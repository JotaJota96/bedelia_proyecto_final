import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'descripcionInscripcionCurso'
})
export class DescripcionInscripcionCursoPipe implements PipeTransform {

  transform(value: number, ...args: unknown[]): string {
    //1 = el estudiante está habilitado para inscribirse, 
    //0 = curso ya aprobado, 
    //-1 = no se cumple con las previas
    
    switch (value) {
      case  1: return "";
      case  0: return "Curso ya aprobado";
      case -1: return "No se cumple con las previas";
      case -2: return "Ya inscrito";
    }
    return "";
  }

}
