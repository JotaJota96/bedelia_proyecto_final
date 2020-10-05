import { Component } from '@angular/core';

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
  
  menu:MenuSection[];

  ngOnInit(): void {
    // aca habra que poner un IF o un SWITCH para cargar el menu segun el rol
    // por ahora lo hardcodeo...
    this.menu = MENU_ADMIN;
  }
}
