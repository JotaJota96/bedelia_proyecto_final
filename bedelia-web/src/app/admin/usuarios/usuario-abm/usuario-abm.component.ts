import { DatePipe, formatDate } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { DireccionDTO } from 'src/app/clases/direccion-dto';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';
import { ActivatedRoute, Params } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';
import { SedesService } from 'src/app/servicios/sedes.service';
import { SedeDTO } from 'src/app/clases/sede-dto';
import { AdministrativosService } from 'src/app/servicios/administrativos.service';


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

export interface ISexo {
  clave: string;
  texto: string;
}

const SEXOS: ISexo[] = [
  { clave: "M", texto: "Masculino" },
  { clave: "F", texto: "Femenino" },
  { clave: "O", texto: "Otro" },
]

const ROLES: string[] = ['admin', 'administrativo', 'docente', 'estudiante']

@Component({
  selector: 'app-usuario-abm',
  templateUrl: './usuario-abm.component.html',
  styleUrls: ['./usuario-abm.component.css']
})
export class UsuarioABMComponent implements OnInit {
  listaDepartamentos: string[] = DEPARTAMENTOS;
  listaSexos: ISexo[] = SEXOS;
  listaRoles: string[] = ROLES;
  rolesSeleccionados: string[] = [];
  rolSeleccionado: string;
  soloLectura: boolean = false;
  listaSedes: SedeDTO[];
  esAdministrativo: boolean = false;
  sedeSeleccionada:SedeDTO = null;

  public formulario: FormGroup;

  constructor(private _snackBar: MatSnackBar, protected sedeServ: SedesService, protected adminisServ: AdministrativosService,
    protected usuServ: UsuariosService, private router: Router, private rutaActiva: ActivatedRoute) { }

  ngOnInit(): void {
    let parametroCi: string = this.rutaActiva.snapshot.params.id;

    if (parametroCi != undefined) {
      this.soloLectura = true
      let usu = new UsuarioDTO();
      this.usuServ.get(parametroCi).subscribe(
        (datos) => {
          this.cargaDeDatos(datos);
        },
        (error) => {
          this.openSnackBar("No se pudo cargar el usuario de la base de dato");
        }
      );
    }

    this.sedeServ.getAll().subscribe(
      (datos) => {
        this.listaSedes = datos;
      },
      (error) => {
        this.openSnackBar("No se pudo cargar las sedes de la base de dato");
      }
    );

    this.formulario = new FormGroup({
      // persona
      cedula: new FormControl('', [Validators.required]),
      nombre: new FormControl('', [Validators.required]),
      apellido: new FormControl('', [Validators.required]),
      correo: new FormControl('', [Validators.required]),
      fecha_nac: new FormControl('', [Validators.required]),
      sexo: new FormControl('', [Validators.required]),
      // direccion
      departamento: new FormControl('', [Validators.required]),
      ciudad: new FormControl('', [Validators.required]),
      calle: new FormControl('', [Validators.required]),
      numero: new FormControl('', [Validators.required]),
      // roles
      roles: new FormControl([], [Validators.required]),
      sede: new FormControl(undefined),
    });
  }

  cargaDeDatos(usu: UsuarioDTO) {
    // persona
    this.formulario.controls['cedula'].setValue(usu.persona.cedula);
    this.formulario.controls['nombre'].setValue(usu.persona.nombre);
    this.formulario.controls['apellido'].setValue(usu.persona.apellido);
    this.formulario.controls['correo'].setValue(usu.persona.correo);
    this.formulario.controls['fecha_nac'].setValue(usu.persona.fecha_nac);
    this.formulario.controls['sexo'].setValue(usu.persona.sexo);
    // direccion
    this.formulario.controls['departamento'].setValue(usu.persona.direccion.departamento);
    this.formulario.controls['ciudad'].setValue(usu.persona.direccion.ciudad);
    this.formulario.controls['calle'].setValue(usu.persona.direccion.calle);
    this.formulario.controls['numero'].setValue(usu.persona.direccion.numero);
    // roles
    this.rolesSeleccionados = usu.roles;
  }

  vaciarDatos() {
    // persona
    this.formulario.controls['cedula'].setValue("");
    this.formulario.controls['nombre'].setValue("");
    this.formulario.controls['apellido'].setValue("");
    this.formulario.controls['correo'].setValue("");
    this.formulario.controls['fecha_nac'].setValue("");
    this.formulario.controls['sexo'].setValue("");
    // direccion
    this.formulario.controls['departamento'].setValue("");
    this.formulario.controls['ciudad'].setValue("");
    this.formulario.controls['calle'].setValue("");
    this.formulario.controls['numero'].setValue("");
    // roles
    this.formulario.controls['roles'].setValue("");
  }

  agregarRol() {
    if (this.rolesSeleccionados.includes(this.rolSeleccionado)) {
      return;
    }
    if (this.rolSeleccionado == "administrativo") {
      this.esAdministrativo = true;
    }
    this.rolesSeleccionados.push(this.rolSeleccionado);
    this.formulario.controls['roles'].setValue(this.rolesSeleccionados);
  }

  agregar() {
    let usu: UsuarioDTO = new UsuarioDTO();
    usu.persona = new PersonaDTO();
    usu.persona.direccion = new DireccionDTO();

    usu.roles = this.formulario.controls['roles'].value;

    usu.persona.cedula = this.formulario.controls['cedula'].value;
    usu.persona.nombre = this.formulario.controls['nombre'].value;
    usu.persona.apellido = this.formulario.controls['apellido'].value;
    usu.persona.correo = this.formulario.controls['correo'].value;
    usu.persona.fecha_nac = this.formulario.controls['fecha_nac'].value;
    usu.persona.sexo = this.formulario.controls['sexo'].value;
    usu.persona.direccion.departamento = this.formulario.controls['departamento'].value;
    usu.persona.direccion.ciudad = this.formulario.controls['ciudad'].value;
    usu.persona.direccion.calle = this.formulario.controls['calle'].value;
    usu.persona.direccion.numero = this.formulario.controls['numero'].value;

    usu.persona.fecha_nac = formatDate(usu.persona.fecha_nac, 'yyyy-MM-dd', 'en-US');

    usu.contrasenia = "1234";
    
    if (this.esAdministrativo == true) {
      if(this.formulario.controls['sede'].value == undefined){
        this.openSnackBar("Se deve seleccionar una sede")
        return;
      }
    }
    
    let sede: SedeDTO = this.formulario.controls['sede'].value;

    this.usuServ.create(usu).subscribe(
      (datos) => {
        if (this.esAdministrativo == true) {
          this.adminisServ.asignar(sede, this.formulario.controls['cedula'].value).subscribe(
            (datos) => {
              this.formulario.controls['sede'].setValue(undefined);
            },
            (error) => {
              this.openSnackBar("No se pudo asignar la sede")
            }
          );
        }
        this.router.navigate(['/admin/usuarios']);
      },
      (error) => {
        this.openSnackBar("No se pudo crear el usuario")
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