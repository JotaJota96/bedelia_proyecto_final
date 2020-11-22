import { Component, HostListener } from '@angular/core';
import { Router } from '@angular/router';
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
      {nombre: "Usuarios",            link: "admin/usuarios"},
    ],
  }, {
    nombre: "Gestión de carreras",
    items: [
      {nombre: "Carreras",           link: "admin/carrera"},
      {nombre: "Cursos",             link: "admin/curso"},
      {nombre: "Áreas de estudio",   link: "admin/area"},
      {nombre: "Tipos de curso",     link: "admin/tipo"},
    ],
  }, {
    nombre: "Otros",
    items: [
      {nombre: "Año lectivo",   link: "admin/periodos"},
      {nombre: "Sedes",         link: "admin/sede"},
    ],
  }, {
    nombre: "Mis datos",
    items: [
      {nombre: "Cambiar contraseña ",   link: "usuarios/cambiar/contrasenia"},
    ],
  }
];
const MENU_ADMINISTRATIVO:MenuSection[] = [
  {
    nombre: "Administración",
    items: [
      {nombre: "Asignar docente",                        link: "administrativo/asignar-docente"},
      {nombre: "Revisar postulaciones",                  link: "administrativo/revicion-postulante"},
      {nombre: "Revisar actas de cursos / exámenes",     link: "administrativo/revicion-acta"},
      {nombre: "Justificar inasistencias",               link: "administrativo/justificar-inasistencia"},
    ],
  }, {
    nombre: "Mis datos",
    items: [
      {nombre: "Cambiar contraseña ",   link: "usuarios/cambiar/contrasenia"},
    ],
  }
];
const MENU_DOCENTE:MenuSection[] = [
  {
    nombre: "Área docente",
    items: [
      {nombre: "Ingresar resultados de cursos",   link: "docente/ingreso/resultados-curso"},
      {nombre: "Ingresar resultados de exámenes", link: "docente/ingreso/resultados-examen"},
      {nombre: "Control de asistencia",           link: "docente/control-asistencia"},
    ],
  }, {
    nombre: "Mis datos",
    items: [
      {nombre: "Cambiar contraseña ",   link: "usuarios/cambiar/contrasenia"},
    ],
  }
];
const MENU_ESTUDIANTE:MenuSection[] = [
  {
    nombre: "Académico",
    items: [
      {nombre: "Escolaridad",            link: "estudiante/consultar-escolaridad"},
      {nombre: "Inscripción a cursos",   link: "estudiante/inscripcion/curso"},
      {nombre: "Inscripción a exámenes", link: "estudiante/inscripcion/examen"},
    ],
  }, {
    nombre: "Mis datos",
    items: [
      {nombre: "Cambiar contraseña ",   link: "usuarios/cambiar/contrasenia"},
    ],
  }
];

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  
  menuMode:string = "side";
  
  constructor(
    protected UsuServ:UsuariosService,
    private router:Router) { }
  
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
    this.router.navigate(['/']);
  }

  @HostListener('window:resize', ['$event'])
  onResize(event) {
    if (window.innerWidth >= 992){
      this.menuMode = "side";
    }else{
      this.menuMode = "over";
    }
  }
}
