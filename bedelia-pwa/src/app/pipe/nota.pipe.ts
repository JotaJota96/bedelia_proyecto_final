import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'nota'
})
export class NotaPipe implements PipeTransform {

  transform(value: number): string {
    if(value <= 0){
      return "Sin calificar"
    }else{
      return value+""
    }

  }
}
