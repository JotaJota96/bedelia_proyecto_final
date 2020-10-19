import { Component, OnInit, ɵConsole } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { PostulanteDTO } from 'src/app/clases/postulante-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { PostulanteService } from 'src/app/servicios/postulante.service';
import { SedesService } from 'src/app/servicios/sedes.service';
import { ModalInformarComponent } from './modal-informar/modal-informar.component';

@Component({
  selector: 'app-revicion-inscripciones-postulantes',
  templateUrl: './revicion-inscripciones-postulantes.component.html',
  styleUrls: ['./revicion-inscripciones-postulantes.component.css']
})
export class RevicionInscripcionesPostulantesComponent implements OnInit {
  columnasAMostrar: string[] = ['id', 'persona', 'accion'];
  sedeDataSource = new MatTableDataSource([]);

  Elusuario: UsuarioDTO;
  elMensaje: string;

  verDocumentacion: boolean = false;
  postulanteSeleccionado: PostulanteDTO;

  constructor(protected sedesServ: SedesService, private _snackBar: MatSnackBar, protected postulanteServ: PostulanteService) { }

  ngOnInit(): void {

    this.sedesServ.getSedes(1).subscribe(
      (datos) => {
        this.sedeDataSource.data = datos;
      }, (error) => {
        this.openSnackBar("No se pudieron traer los postulante de la base de dato");
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
