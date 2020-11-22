import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'inasistenca'
})
export class InasistencaPipe implements PipeTransform {

  transform(value: number): string {
    if(value < 0){
      return "Máximo superado"
    }else{
      return value+""
    }

  }

}
