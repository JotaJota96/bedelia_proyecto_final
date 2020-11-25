import { ReturnStatement } from '@angular/compiler';
import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'sexo'
})
export class SexoPipe implements PipeTransform {

  transform(value: string): string {
    if(value == "M"){
      return "Masculino"
    }
    if(value == "F"){
      return "Femenino"
    }
    if(value == "O"){
      return "Otro"
    }
  }

}
