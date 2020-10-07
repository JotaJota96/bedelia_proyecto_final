import { Component } from '@angular/core';
import { UsuariosService } from './servicios/usuarios.service';

// MenuSection y MenuItem sirven para estructurar el menú de la izquierda
export interface MenuItem {
  nombre:String;
  link:String;
}
export interface MenuSection {
  nombre:String;
  items:MenuItem[];
}

// Definicion de menus para todos los roles
const MENU_ADMIN:MenuSection[] = [
  {
    nombre: "Gestión de usuario",
    items: [
      {nombre: "Admins",            link: "#"},
      {nombre: "Docentes",          link: "#"},
      {nombre: "Administrativos",   link: "#"},
    ],
  }, {
    nombre: "Gestión de carreras",
    items: [
      {nombre: "Carreras",           link: "#"},
      {nombre: "Cursos",             link: "#"},
      {nombre: "Áreas de estudio",   link: "#"},
      {nombre: "Tipos de curso",     link: "#"},
    ],
  }, {
    nombre: "Otros",
    items: [
      {nombre: "Año lectivo",   link: "#"},
      {nombre: "Cedes",         link: "#"},
    ],
  },
];
const MENU_ADMINISTRATIVO:MenuSection[] = [
  {
    nombre: "Administración",
    items: [
      {nombre: "Asignar docente",             link: "#"},
      {nombre: "Revisar postulaciónes",       link: "#"},
      {nombre: "Revisar actas de exámenes",   link: "#"},
      {nombre: "Revisar actas de cursos",     link: "#"},
      {nombre: "Justificar inasistencias",    link: "#"},
    ],
  },
];
const MENU_DOCENTE:MenuSection[] = [
  {
    nombre: "Área docente",
    items: [
      {nombre: "Ingresar resultados de exámenes", link: "#"},
      {nombre: "Ingresar resultados de cursos",   link: "#"},
      {nombre: "Control de asistencia",           link: "#"},
    ],
  },
];
const MENU_ESTUDIANTE:MenuSection[] = [
  {
    nombre: "Datos",
    items: [
      {nombre: "Mis datos",   link: "#"},
    ],
  }, {
    nombre: "Académico",
    items: [
      {nombre: "Escolaridad",            link: "#"},
      {nombre: "Inscripcion a cursos",   link: "#"},
      {nombre: "Inscripcion a exámenes", link: "#"},
      {nombre: "Año lectivo",            link: "#"},
    ],
  },
];

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  
  constructor(protected UsuServ:UsuariosService) { }
  
  ngOnInit(): void {
  }

  public menuAMostrar():MenuSection[]{
    // Devuelve el menu que corresponda segun el rol
    if (this.UsuServ.isLogged()){
      if(this.UsuServ.isEstudiante()){
        return MENU_ESTUDIANTE;
      }
      if(this.UsuServ.isAdmin()){
        return MENU_ADMIN;
      }
      if(this.UsuServ.isDocente()){
        return MENU_DOCENTE;
      }
      if(this.UsuServ.isAdministrativo()){
        return MENU_ADMINISTRATIVO;
      }
    }
    return undefined;
  }

  logeado():boolean{
    return this.UsuServ.isLogged()
  }
  logout(){
    this.UsuServ.logout();
  }
}
