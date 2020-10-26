import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-justificar-inasistencia',
  templateUrl: './justificar-inasistencia.component.html',
  styleUrls: ['./justificar-inasistencia.component.css']
})
export class JustificarInasistenciaComponent implements OnInit {
  mostrarDatos:boolean = false
  persona:PersonaDTO;

  public formulario: FormGroup;
  public formularioJustificar: FormGroup;
  constructor(private _snackBar: MatSnackBar, protected usuServ: UsuariosService) { }

  ngOnInit(): void {
    
    this.formulario = new FormGroup({
      ci: new FormControl('', [Validators.required]),
    });

    this.formularioJustificar = new FormGroup({
      fechaInicio: new FormControl('', [Validators.required]),
      fechaFin: new FormControl('', [Validators.required]),
    });
  }

  buscar(){
    this.mostrarDatos = true;
    this.usuServ.get(this.formulario.controls['ci'].value).subscribe(
      (datos)=>{
        this.persona = datos.persona;
      },
      (error)=>{
        this.openSnackBar("Error al traer los datos del estudiante");
      }
    );
  }

  justificar(){
    this.formulario.controls['fechaInicio'].value
    this.formulario.controls['fechaFin'].value
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
