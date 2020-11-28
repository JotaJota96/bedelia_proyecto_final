import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'optativoObligatorio'
})
export class OptativoObligatorioPipe implements PipeTransform {

  transform(value: any, ...args: unknown[]): unknown {
    if (value == true){
      return "Optativo";
    } else if (value == false){
      return "Obligatorio";
    }
    return "-";
  }

}
