import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'roles'
})
export class RolesPipe implements PipeTransform {

  transform(value: string[]): string {
    let ret = "";
    
    // si no hay roles
    if(value.length==0){
      ret = "sin roles";
      return ret;
    }

    // si SI hay roles, los concatena
    value.forEach(element => {
      ret += element + ", ";
    });

    // termina sobrando un ", " asi que se saca
    ret = ret.substring(0, ret.length - 2);

    return ret;
  }

}
