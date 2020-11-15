/**
 * Escriba aqui sus funciones globales
 */

import { MatSnackBar } from '@angular/material/snack-bar';

export function openSnackBar(_snackBar: MatSnackBar, mensaje : string) {
  _snackBar.open(mensaje,'Salir', {
    duration: 5000,
    horizontalPosition: 'center',
    verticalPosition: "top",
  });
}