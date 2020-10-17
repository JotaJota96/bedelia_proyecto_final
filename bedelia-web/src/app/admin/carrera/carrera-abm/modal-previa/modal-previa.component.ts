import { Component, Inject, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { PreviaDTO } from 'src/app/clases/previa-dto';

interface datosEntrada {
  curso: CursoDTO;
  listaCursos: CursoDTO[];
  listaPrevia: PreviaDTO[];
}

@Component({
  selector: 'app-modal-previa',
  templateUrl: './modal-previa.component.html',
  styleUrls: ['./modal-previa.component.css']
})
export class ModalPreviaComponent implements OnInit {

  curso: CursoDTO = this.data.curso;
  listaCurso: CursoDTO[] = this.data.listaCursos;
  listaPrevia: PreviaDTO[] = this.data.listaPrevia;

  public formularioPrevia: FormGroup;
  constructor(public dialogRef: MatDialogRef<ModalPreviaComponent>,
    @Inject(MAT_DIALOG_DATA) public data: datosEntrada) { }

  ngOnInit(): void {

    this.formularioPrevia = new FormGroup({
      idPrevia: new FormControl('', [Validators.required]),
      tipoPrevia: new FormControl('', [Validators.required]),
    });
    
  }

  asignarPrevia() {
    let salir = false;
    let previa : PreviaDTO = new PreviaDTO();
    previa.curso_id = this.curso.id;
    previa.nombre_carrera_previa = (this.formularioPrevia.controls['idPrevia'].value).nombre;
    previa.curso_id_previa = (this.formularioPrevia.controls['idPrevia'].value).id;
    previa.tipo = this.formularioPrevia.controls['tipoPrevia'].value;

    this.listaPrevia.forEach(element => {
      if(element.curso_id_previa == previa.curso_id_previa) {
        salir = true;
      }
    });

    if(salir){
      return;
    }
    
    
    this.listaPrevia.push(previa);
    
  }

  aceptar(){
    this.dialogRef.close(this.data);
  }
}

