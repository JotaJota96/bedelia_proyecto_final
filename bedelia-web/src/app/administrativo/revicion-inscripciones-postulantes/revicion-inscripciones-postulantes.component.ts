import { Component, OnInit, ɵConsole } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { PostulanteDTO } from 'src/app/clases/postulante-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { PostulanteService } from 'src/app/servicios/postulante.service';
import { ModalInformarComponent } from './modal-informar/modal-informar.component';

@Component({
  selector: 'app-revicion-inscripciones-postulantes',
  templateUrl: './revicion-inscripciones-postulantes.component.html',
  styleUrls: ['./revicion-inscripciones-postulantes.component.css']
})
export class RevicionInscripcionesPostulantesComponent implements OnInit {
  listaPostulante: PostulanteDTO[];
  Elusuario: UsuarioDTO;
  elMensaje: string;

  verDocumentacion: boolean = false;
  postulanteSeleccionado: PostulanteDTO = null;

  constructor(public dialog: MatDialog, private _snackBar: MatSnackBar, protected postulanteServ: PostulanteService) { }

  ngOnInit(): void {

    this.postulanteServ.getAll().subscribe(
      (datos) => {
        this.listaPostulante = datos;
      }, (error) => {
        this.openSnackBar("No se pudieron traer los postulante de la base de dato");
      }
    );
  }

  verMas(idPostulante: number) {
    this.verDocumentacion = false;
    this.listaPostulante.forEach(element => {
      if (element.id == idPostulante) {
        this.postulanteSeleccionado = element;
      }
    });
  }

  verDocumentos(){
    this.verDocumentacion = !this.verDocumentacion;
  }

  informarProblema(){
    const dialogRef = this.dialog.open(ModalInformarComponent);

    dialogRef.afterClosed().subscribe(result => {
      this.elMensaje = result;
    });
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
