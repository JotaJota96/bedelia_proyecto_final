import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef } from '@angular/material/dialog';

@Component({
  selector: 'app-ingresar-nota-examen',
  templateUrl: './ingresar-nota-examen.component.html',
  styleUrls: ['./ingresar-nota-examen.component.css']
})
export class IngresarNotaExamenComponent implements OnInit {

  public formulario: FormGroup;
  
  constructor(public dialogRef: MatDialogRef<IngresarNotaExamenComponent>) {}

  ngOnInit(): void {
    this.formulario = new FormGroup({
      nota: new FormControl('', [Validators.required])
    });
  }

  enviar(){
    this.dialogRef.close(this.formulario.controls['nota'].value);
  }
}
