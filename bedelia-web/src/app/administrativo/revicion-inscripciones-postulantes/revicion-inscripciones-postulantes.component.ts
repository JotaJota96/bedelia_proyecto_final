import { Component, OnInit, ɵConsole } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { PostulanteDTO } from 'src/app/clases/postulante-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AdministrativosService } from 'src/app/servicios/administrativos.service';
import { PostulanteService } from 'src/app/servicios/postulante.service';
import { SedesService } from 'src/app/servicios/sedes.service';

@Component({
  selector: 'app-revicion-inscripciones-postulantes',
  templateUrl: './revicion-inscripciones-postulantes.component.html',
  styleUrls: ['./revicion-inscripciones-postulantes.component.css']
})
export class RevicionInscripcionesPostulantesComponent implements OnInit {
  columnasAMostrar: string[] = ['id', 'cedula', 'carrera', 'estado', 'accion'];
  sedeDataSource = new MatTableDataSource([]);

  Elusuario: UsuarioDTO;
  elMensaje: string;
  idSedeAdministrativo: number;
  ciLogeado: string = JSON.parse(localStorage.getItem("loginData")).cedula;
  verDocumentacion: boolean = false;
  postulanteSeleccionado: PostulanteDTO;
  sedeOk: boolean = undefined;
  
  constructor(protected administrativoServ: AdministrativosService, protected sedesServ: SedesService, private _snackBar: MatSnackBar, protected postulanteServ: PostulanteService) { }

  ngOnInit(): void {
    this.administrativoServ.get(this.ciLogeado).subscribe(
      (datosSede) => {
        this.sedeOk = datosSede.id != null;
        if (this.sedeOk) {
          this.sedesServ.getSedes(datosSede.id).subscribe(
            (datos) => {
              this.sedeDataSource.data = datos;
            }, (error) => {
              openSnackBar(this._snackBar, "Error al obtener los postulantes de la sede");
            }
          );
        }
      },
      (error) => {
        openSnackBar(this._snackBar, "No se pudieron cargar los datos");
      }
    )
  }

}
