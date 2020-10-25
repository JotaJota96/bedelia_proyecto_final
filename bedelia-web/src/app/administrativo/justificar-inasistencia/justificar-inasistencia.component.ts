import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-justificar-inasistencia',
  templateUrl: './justificar-inasistencia.component.html',
  styleUrls: ['./justificar-inasistencia.component.css']
})
export class JustificarInasistenciaComponent implements OnInit {

  columnasAMostrar: string[] = ['cedula', 'accion', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  usuariosDataSource = new MatTableDataSource([]);

  public formulario: FormGroup;
  constructor(private _snackBar: MatSnackBar, protected usuServ: UsuariosService) { }

  ngOnInit(): void {
    
    this.formulario = new FormGroup({
      ci: new FormControl('', [Validators.required]),
    });
  }

  justificar(ci:string){

  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
