import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'fechaNull'
})
export class FechaNullPipe implements PipeTransform {

  transform(value: string): string {
    if(value == null){
      return "Fecha no defenida";
    }else{
      return value;
    }
  }

}
