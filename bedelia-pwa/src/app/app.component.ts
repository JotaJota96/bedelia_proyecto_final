import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AccountService } from './servis/account.service';

// MenuSection y MenuItem sirven para estructurar el menú de la izquierda
export interface MenuItem {
  nombre:String;
  link:String;
}
export interface MenuSection {
  nombre:String;
  items:MenuItem[];
}

const MENU_ESTUDIANTE:MenuSection[] = [
  {
    nombre: "Datos",
    items: [
      {nombre: "Cambiar contraseña ",   link: "estudiante/cambiar/contrasenia"},
    ],
  }, {
    nombre: "Académico",
    items: [
      {nombre: "Escolaridad",            link: "estudiante/consultar-escolaridad"},
      {nombre: "Inscripcion a cursos",   link: "estudiante/inscripcion/curso"},
      {nombre: "Inscripcion a exámenes", link: "estudiante/inscripcion/examen"},
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
  constructor(protected AccServ:AccountService,
    private router:Router) { }
  
  ngOnInit(): void {
  }
  public menuAMostrar():MenuSection[]{
    return MENU_ESTUDIANTE;
  }

  logeado():boolean{
    return this.AccServ.isLogged()
  }
  logout(){
    this.AccServ.logout();
    this.router.navigate(['/']);
  }
}
