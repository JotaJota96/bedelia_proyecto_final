import { formatDate } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ActivatedRoute } from '@angular/router';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { DireccionDTO } from 'src/app/clases/direccion-dto';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { PostulanteDTO } from 'src/app/clases/postulante-dto';
import { SedeDTO } from 'src/app/clases/sede-dto';
import { CarreraService } from 'src/app/servicios/carrera.service';
import { PostulanteService } from 'src/app/servicios/postulante.service';
import { SedesService } from 'src/app/servicios/sedes.service';

export interface ISexo {
  clave: string;
  texto: string;
}

const SEXOS: ISexo[] = [
  { clave: "M", texto: "Masculino" },
  { clave: "F", texto: "Femenino" },
  { clave: "O", texto: "Otro" },
]

const DEPARTAMENTOS: string[] = [
  'Artigas',
  'Canelones',
  'Cerro Largo',
  'Colonia',
  'Durazno',
  'Flores',
  'Florida',
  'Lavalleja',
  'Maldonado',
  'Montevideo',
  'Paysandú',
  'Río Negro',
  'Rivera',
  'Rocha',
  'Salto',
  'San José',
  'Soriano',
  'Tacuarembó',
  'Treinta y Tres',
]

@Component({
  selector: 'app-inscripcion-carrera',
  templateUrl: './inscripcion-carrera.component.html',
  styleUrls: ['./inscripcion-carrera.component.css']
})
export class InscripcionCarreraComponent implements OnInit {
  
  carrera: CarreraDTO;
  listaSedes: SedeDTO[];
  listaSexos: ISexo[] = SEXOS;  
  soloLectura: boolean = false;
  listaDepartamentos: string[] = DEPARTAMENTOS;

  public formulario: FormGroup;
  constructor(private _snackBar: MatSnackBar, protected postulanteServ: PostulanteService, protected sedeServ: SedesService, protected carreraServ:CarreraService, private rutaActiva: ActivatedRoute) { }

  ngOnInit(): void {
    
    let parametrosId: number = this.rutaActiva.snapshot.params.id;

    if (parametrosId != undefined) {
      this.carreraServ.get(parametrosId).subscribe(
        (datos) => {
          this.carrera = datos;
        },
        (error) => {
          this.openSnackBar("Error al cargar la carrera de la base de dato");
        }
      );
    }

    this.formulario = new FormGroup({
      cedula: new FormControl('', [Validators.required]),
      nombre: new FormControl('', [Validators.required]),
      apellido: new FormControl('', [Validators.required]),
      correo: new FormControl('', [Validators.required]),
      fechaNac : new FormControl('', [Validators.required]),
      sexo : new FormControl('', [Validators.required]),
      sede : new FormControl('', [Validators.required]),
      // direccion
      departamento: new FormControl('', [Validators.required]),
      ciudad: new FormControl('', [Validators.required]),
      calle: new FormControl('', [Validators.required]),
      numero: new FormControl('', [Validators.required]),
    });

    // obtiene todos las sedes
    this.sedeServ.getAll().subscribe(
      (datos) => {
        this.listaSedes = datos;
      }, (error) => {
        this.openSnackBar("Error al cargar las sedes de la base de dato");
      }
    );
  }

  enviar(){
    let postulante: PostulanteDTO   = new PostulanteDTO();
    postulante.persona              = new PersonaDTO();
    postulante.persona.direccion    = new DireccionDTO();

    postulante.persona.cedula                   = this.formulario.controls['cedula'].value;
    postulante.persona.nombre                   = this.formulario.controls['nombre'].value;
    postulante.persona.apellido                 = this.formulario.controls['apellido'].value;
    postulante.persona.correo                   = this.formulario.controls['correo'].value;
    postulante.persona.fecha_nac                = this.formulario.controls['fechaNac'].value;
    postulante.persona.sexo                     = this.formulario.controls['sexo'].value;
    postulante.persona.direccion.departamento   = this.formulario.controls['departamento'].value;
    postulante.persona.direccion.ciudad         = this.formulario.controls['ciudad'].value;
    postulante.persona.direccion.calle          = this.formulario.controls['calle'].value;
    postulante.persona.direccion.numero         = this.formulario.controls['numero'].value;
    postulante.sede                             = this.formulario.controls['sede'].value;
    postulante.persona.fecha_nac                = formatDate(postulante.persona.fecha_nac, 'yyyy-MM-dd', 'en-US');

    

    this.postulanteServ.create(postulante).subscribe(
      (datos) => {
        this.openSnackBar("Correcto");
      },
      (error) => {
        this.openSnackBar("No se pudo crear el curso");
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
