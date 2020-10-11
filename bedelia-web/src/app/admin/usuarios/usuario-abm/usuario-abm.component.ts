import { DatePipe, formatDate } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { utimes } from 'fs';
import { utils } from 'protractor';
import { DireccionDTO } from 'src/app/clases/direccion-dto';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';


const DEPARTAMENTOS:string[] = [
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

export interface ISexo{
  clave:string;
  texto:string;
}

const SEXOS:ISexo[] = [
  {clave: "M", texto: "Masculino"},
  {clave: "F", texto: "Femenino"},
  {clave: "O", texto: "Otro"},
]

const ROLES:string[] = ['admin', 'administrativo', 'docente', 'estudiante']

@Component({
  selector: 'app-usuario-abm',
  templateUrl: './usuario-abm.component.html',
  styleUrls: ['./usuario-abm.component.css']
})
export class UsuarioABMComponent implements OnInit {
  listaDepartamentos:string[] = DEPARTAMENTOS;
  listaSexos:ISexo[] = SEXOS;
  listaRoles:string[] = ROLES;
  rolesSeleccionados:string[] = [];
  rolSeleccionado:string;

  public formulario: FormGroup;

  constructor(protected usuServ:UsuariosService,
    private router:Router) { }

  ngOnInit(): void {
    this.formulario = new FormGroup({
      // persona
      cedula:    new FormControl('', [Validators.required]),
      nombre:    new FormControl('', [Validators.required]),
      apellido:  new FormControl('', [Validators.required]),
      correo:    new FormControl('', [Validators.required]),
      fecha_nac: new FormControl('', [Validators.required]),
      sexo:      new FormControl('', [Validators.required]),
      // direccion
      departamento: new FormControl('', [Validators.required]),
      ciudad:       new FormControl('', [Validators.required]),
      calle:        new FormControl('', [Validators.required]),
      numero:       new FormControl('', [Validators.required]),
      // roles
      roles: new FormControl([], [Validators.required]),
    });
  }
  
  agregarRol(){
    if (this.rolesSeleccionados.includes(this.rolSeleccionado)){
      return;
    }
    this.rolesSeleccionados.push(this.rolSeleccionado);
    this.formulario.controls['roles'].setValue(this.rolesSeleccionados);
  }

  agregar(){
    let usu:UsuarioDTO = new UsuarioDTO();
    usu.persona = new PersonaDTO();
    usu.persona.direccion = new DireccionDTO();

    usu.roles = this.formulario.controls['roles'].value;

    usu.persona.cedula    = this.formulario.controls['cedula'].value;
    usu.persona.nombre    = this.formulario.controls['nombre'].value;
    usu.persona.apellido  = this.formulario.controls['apellido'].value;
    usu.persona.correo    = this.formulario.controls['correo'].value;
    usu.persona.fecha_nac = this.formulario.controls['fecha_nac'].value;
    usu.persona.sexo      = this.formulario.controls['sexo'].value;
    usu.persona.direccion.departamento = this.formulario.controls['departamento'].value;
    usu.persona.direccion.ciudad       = this.formulario.controls['ciudad'].value;
    usu.persona.direccion.calle        = this.formulario.controls['calle'].value;
    usu.persona.direccion.numero       = this.formulario.controls['numero'].value;

    usu.persona.fecha_nac = formatDate(usu.persona.fecha_nac, 'yyyy-MM-dd', 'en-US');


    usu.contrasenia = "1234";

    this.usuServ.create(usu).subscribe(
      (datos)=>{
        alert("Hecho");
        this.router.navigate(['/admin/usuarios']);
      },
      (error) =>{
        alert("Error");
      }
    );
    
  }
}





/*
{
  "persona": {
    "direccion": {
      "departamento": "San José",
      "ciudad": "san jose de mayo",
      "calle": "españa",
      "numero": "1041"
    },
    "cedula": "47101475",
    "nombre": "Juan",
    "apellido": "Alvarez",
    "correo": "jjap96@gmail.com",
    "fecha_nac": "2020-10-01T03:00:00.000Z",
    "sexo": "M"
  },
  "contrasenia": "1234",
  "roles": [
    "estudiante"
  ]
}

{
  "id": 0,
  "contrasenia": "string",
  "roles": [
    "string"
  ],
  "persona": {
    "id": 0,
    "cedula": "string",
    "nombre": "string",
    "apellido": "string",
    "correo": "string",
    "fecha_nac": "string",
    "sexo": "string",
    "direccion": {
      "departamento": "string",
      "ciudad": "string",
      "calle": "string",
      "numero": "string"
    }
  }
}
*/