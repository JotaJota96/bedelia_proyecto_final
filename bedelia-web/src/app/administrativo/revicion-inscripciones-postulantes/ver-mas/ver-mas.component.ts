import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute } from '@angular/router';
import { PostulanteDTO } from 'src/app/clases/postulante-dto';
import { PostulanteService } from 'src/app/servicios/postulante.service';
import { ModalInformarComponent } from '../modal-informar/modal-informar.component';

@Component({
  selector: 'app-ver-mas',
  templateUrl: './ver-mas.component.html',
  styleUrls: ['./ver-mas.component.css']
})
export class VerMasComponent implements OnInit {

  constructor(public dialog: MatDialog, private rutaActiva: ActivatedRoute, protected postulanteServis: PostulanteService) { }

  elMensaje:string;
  postulante:PostulanteDTO = new PostulanteDTO;

  ngOnInit(): void {

    let parametrosId: number = this.rutaActiva.snapshot.params.id;

    if (parametrosId != undefined) {
      this.postulanteServis.get(parametrosId).subscribe(
        (datos) => {
          this.postulante = datos;
        }
      );
    }

  }

  verDocumentos() {
    
  }

  informarProblema(id: number) {
    const dialogRef = this.dialog.open(ModalInformarComponent);

    dialogRef.afterClosed().subscribe(result => {
      this.elMensaje = result;
    });
  }

  rechasar(id: number){

  }

  aceptar(id: number){

  }
}
