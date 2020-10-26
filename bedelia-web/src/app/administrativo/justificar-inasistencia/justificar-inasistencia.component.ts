import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { error } from 'protractor';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { EstudianteService } from 'src/app/servicios/estudiante.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-justificar-inasistencia',
  templateUrl: './justificar-inasistencia.component.html',
  styleUrls: ['./justificar-inasistencia.component.css']
})
export class JustificarInasistenciaComponent implements OnInit {
  mostrarDatos:boolean = false
  persona:PersonaDTO = new PersonaDTO;

  public formulario: FormGroup;
  public formularioJustificar: FormGroup;
  constructor(private router: Router, private _snackBar: MatSnackBar, protected usuServ: UsuariosService,protected estServ: EstudianteService) { }

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
    this.estServ.justificarInasistencia(this.formulario.controls['ci'].value, this.formularioJustificar.controls['fechaInicio'].value, this.formularioJustificar.controls['fechaFin'].value).subscribe(
      (datos)=>{
        this.router.navigate(['/']);
      },
      (error)=>{
        this.openSnackBar("Error al justificar las inasistencias")
      }
    );
    
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
