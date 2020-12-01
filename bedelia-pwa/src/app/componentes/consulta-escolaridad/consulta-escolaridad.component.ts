import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { ConnectionService } from 'ng-connection-service';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { EscolaridadDTO } from 'src/app/clases/escolaridad-dto';
import { EstudianteService } from 'src/app/servis/estudiante.service';
import { UsuariosService } from 'src/app/servis/usuarios.service';

@Component({
  selector: 'app-consulta-escolaridad',
  templateUrl: './consulta-escolaridad.component.html',
  styleUrls: ['./consulta-escolaridad.component.css']
})
export class ConsultaEscolaridadComponent implements OnInit {
  listaCarrera : CarreraDTO[] = [];
  escolaridad: EscolaridadDTO = null;
  ciLogeado: string;
  mostrarSpinner:boolean = false;

  constructor(private onlineService: ConnectionService,private router: Router, private _snackBar: MatSnackBar,
     protected estudianteServ: EstudianteService, protected usuServ: UsuariosService) { }

  
  public formulario: FormGroup;
  ngOnInit(): void {

    if(!navigator.onLine){
      this.router.navigate(['/desconectado']);
      return;
    }
    
    this.onlineService.monitor().subscribe(
      (conectado)=>{
        if(!conectado){
          this.router.navigate(['/desconectado']);
          return;
        }
      }
    );

    this.ciLogeado = this.usuServ.obtenerDatosLoginAlmacenado().cedula;
    this.estudianteServ.getCarreras(this.ciLogeado).subscribe(
      (datos)=>{
        this.listaCarrera = datos;
      },
      (error)=>{
        this.openSnackBar("Error al cargar las carreras");
      }
    );

    this.formulario = new FormGroup({
      carrera: new FormControl('', [Validators.required]),
    });
  }

  obtenerEscolaridad(){
    this.mostrarSpinner = true;
    this.estudianteServ.getEscolaridad(this.ciLogeado,this.formulario.controls['carrera'].value).subscribe(
      (datos)=>{
        this.mostrarSpinner = false;
        this.escolaridad = datos;
      },
      (error)=>{
        this.mostrarSpinner = false;
        this.openSnackBar("No se pudo cargar la escolaridad");
      }
    );
  }


  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 4000,
      horizontalPosition: 'center',
      verticalPosition: "bottom",
    });
  }

}
