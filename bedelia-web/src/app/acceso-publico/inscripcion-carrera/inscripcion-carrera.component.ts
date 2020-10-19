import { formatDate } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ActivatedRoute, Router } from '@angular/router';
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
  tipo: number =0;

  public formulario: FormGroup;
  constructor(private router: Router, private _snackBar: MatSnackBar, protected postulanteServ: PostulanteService, protected carreraServ: CarreraService, private rutaActiva: ActivatedRoute) { }

  ngOnInit(): void {

    let parametrosId: number = this.rutaActiva.snapshot.params.id;

    if (parametrosId != undefined) {
      this.carreraServ.get(parametrosId).subscribe(
        (datos) => {
          this.carrera = datos;
          this.listaSedes = datos.sedes;
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
      fechaNac: new FormControl('', [Validators.required]),
      sexo: new FormControl('', [Validators.required]),
      // direccion
      departamento: new FormControl('', [Validators.required]),
      ciudad: new FormControl('', [Validators.required]),
      calle: new FormControl('', [Validators.required]),
      numero: new FormControl('', [Validators.required]),
      //
      sede: new FormControl('', [Validators.required]),
      img_ci: new FormControl('', [Validators.required]),
      img_escolaridad: new FormControl('', [Validators.required]),
      img_carne_salud: new FormControl('', [Validators.required]),
    });
  }

  alCargarImagen(evt: any, tipo: number) {
    this.tipo = tipo;
    const archivo = evt.target.files[0];
    // Si realmente se cargo un archivo
    if (archivo) {
      const lector = new FileReader();
      lector.onload = this.obtenerStringImagen.bind(this);
      lector.readAsBinaryString(archivo);
      // OJO que el string con la imagen demora unos milisegundos en cargarse

    } else {
      // aca no se como hacer que entre, pero por las dudas le pongo esto...
      if (tipo == 0) {
        this.formulario.controls['img_ci'].setValue("");
      }
      if (tipo == 1) {
        this.formulario.controls['img_escolaridad'].setValue("");
      }
      if (tipo == 2) {
        this.formulario.controls['img_carne_salud'].setValue("");
      }
      //this.restablecerAImagenPorDefecto();
    }
  }

  obtenerStringImagen(e) {
    let strImg = "data:image/png;base64," + btoa(e.target.result);

    if (this.tipo == 0) {
      this.formulario.controls['img_ci'].setValue(strImg);
    }
    if (this.tipo == 1) {
      this.formulario.controls['img_escolaridad'].setValue(strImg);
    }
    if (this.tipo == 2) {
      this.formulario.controls['img_carne_salud'].setValue(strImg);
    }
    //this.imagenVistaPrevia = this.profileForm.controls['imagen'].valueimagenVistaPrevia = this.profileForm.controls['imagen'].value;
  }

  enviar() {
    let postulante: PostulanteDTO = new PostulanteDTO();
    postulante.persona = new PersonaDTO();
    postulante.persona.direccion = new DireccionDTO();

    postulante.sede = this.formulario.controls['sede'].value;
    postulante.carrera = this.carrera;
    postulante.img_ci = this.formulario.controls['img_ci'].value;
    postulante.img_escolaridad = this.formulario.controls['img_escolaridad'].value;
    postulante.img_carne_salud = this.formulario.controls['img_carne_salud'].value;

    postulante.persona.cedula = this.formulario.controls['cedula'].value;
    postulante.persona.nombre = this.formulario.controls['nombre'].value;
    postulante.persona.apellido = this.formulario.controls['apellido'].value;
    postulante.persona.correo = this.formulario.controls['correo'].value;
    postulante.persona.fecha_nac = this.formulario.controls['fechaNac'].value;
    postulante.persona.fecha_nac = formatDate(postulante.persona.fecha_nac, 'yyyy-MM-dd', 'en-US');
    postulante.persona.sexo = this.formulario.controls['sexo'].value;

    postulante.persona.direccion.departamento = this.formulario.controls['departamento'].value;
    postulante.persona.direccion.ciudad = this.formulario.controls['ciudad'].value;
    postulante.persona.direccion.calle = this.formulario.controls['calle'].value;
    postulante.persona.direccion.numero = this.formulario.controls['numero'].value;


    console.log(postulante);


    this.postulanteServ.create(postulante).subscribe(
      (datos) => {
        this.router.navigate(['/']);
      },
      (error) => {
        this.openSnackBar("No se pudo mandar la inscripcion");
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
