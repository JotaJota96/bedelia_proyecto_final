import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'tipoActa'
})
export class TipoActaPipe implements PipeTransform {

  transform(ta:string): string {
    if(ta == 'LE'){
      return "Curso"
    }
    if(ta == 'EX'){
      return "Examen"
    }
  }

}

