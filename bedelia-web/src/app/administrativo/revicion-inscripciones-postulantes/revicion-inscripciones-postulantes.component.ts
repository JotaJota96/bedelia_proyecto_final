import { Component, OnInit, ɵConsole } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-revicion-inscripciones-postulantes',
  templateUrl: './revicion-inscripciones-postulantes.component.html',
  styleUrls: ['./revicion-inscripciones-postulantes.component.css']
})
export class RevicionInscripcionesPostulantesComponent implements OnInit {
  typesOfShoes: string[] = ['Boots', 'Clogs', 'Loafers', 'Moccasins', 'Sneakers'];
  // columnas que se mostraran en la tabla
  
  Elusuario: UsuarioDTO;

  constructor(protected usuServ:UsuariosService) { }

  ngOnInit(): void {

    this.usuServ.get("00000000").subscribe(
      (datos) => {
        this.Elusuario = datos;
      }
    );
  }

}
