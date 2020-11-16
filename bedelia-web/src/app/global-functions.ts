/**
 * Escriba aqui sus funciones globales
 */

import { MatSnackBar } from '@angular/material/snack-bar';

export function openSnackBar(_snackBar: MatSnackBar, mensaje : string, tipo : 'ok'|'error'= 'ok') {
  let clasesCss: string[] = [];
  switch (tipo) {
    case 'ok':
      clasesCss = ['mat-snack-bar-container-error'];
      break;
    case 'error':
      clasesCss = ['mat-snack-bar-container-ok'];
      break;
  }

  _snackBar.open(mensaje,'Cerrar', {
    duration: 4000,
    panelClass: clasesCss,
    horizontalPosition: 'center',
    verticalPosition: "top",
  });
}
export function test(){
  openSnackBar(null, "", "error");
}