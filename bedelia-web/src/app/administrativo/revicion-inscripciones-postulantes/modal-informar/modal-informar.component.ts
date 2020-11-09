import { Component, Inject, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

@Component({
  selector: 'app-modal-informar',
  templateUrl: './modal-informar.component.html',
  styleUrls: ['./modal-informar.component.css']
})
export class ModalInformarComponent implements OnInit {

  public formulario: FormGroup;
  
  constructor(public dialogRef: MatDialogRef<ModalInformarComponent>) {}

  ngOnInit(): void {
    this.formulario = new FormGroup({
      mensaje: new FormControl('', [Validators.required])
    });
  }

  enviar(){
    this.dialogRef.close(this.formulario.controls['mensaje'].value);
  }
}
