import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ActivatedRoute, Router } from '@angular/router';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { PostulanteDTO } from 'src/app/clases/postulante-dto';
import { PostulanteService } from 'src/app/servicios/postulante.service';
import { ModalInformarComponent } from '../modal-informar/modal-informar.component';

@Component({
  selector: 'app-ver-mas',
  templateUrl: './ver-mas.component.html',
  styleUrls: ['./ver-mas.component.css']
})
export class VerMasComponent implements OnInit {

  constructor(private router: Router,private _snackBar: MatSnackBar, public dialog: MatDialog, private rutaActiva: ActivatedRoute, protected postulanteServis: PostulanteService) { }

  elMensaje: string;
  postulante: PostulanteDTO = new PostulanteDTO;
  persona: PersonaDTO = new PersonaDTO;

  ngOnInit(): void {

    let parametrosId: number = this.rutaActiva.snapshot.params.id;

    if (parametrosId != undefined) {
      this.postulanteServis.get(parametrosId).subscribe(
        (datos) => {
          this.postulante = datos;
          this.persona = datos.persona;
        }
      );
    }

  }

  verDocumentos() {

  }

  informarProblema() {
    const dialogRef = this.dialog.open(ModalInformarComponent);
    dialogRef.afterClosed().subscribe(result => {
      this.elMensaje = result;
      this.postulanteServis.notificar(this.postulante.id, this.elMensaje).subscribe(
        (datos) => {
          this.openSnackBar("El mensaje fue enviado");
        },
        (error) => {
          this.openSnackBar("No se pudo mandar el mensaje");
        }
      )
    });
  }

  rechasar() {
    this.postulanteServis.rechasar(this.postulante.id).subscribe(
      (datos) => {
        this.router.navigate(['/administrativo/revicion-postulante']);
      },
      (error) => {
        this.openSnackBar("No se pudo rechasar la postulacion");
      }
    )
  }

  aceptar() {
    this.postulanteServis.aceptar(this.postulante.id).subscribe(
      (datos) => {
        this.router.navigate(['/administrativo/revicion-postulante']);
      },
      (error) => {
        this.openSnackBar("No se pudo aceptar la postulacion");
      }
    );
  }

  abrirImagen(base64 :string){
    
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}