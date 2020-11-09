import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef } from '@angular/material/dialog';

@Component({
  selector: 'app-ingresar-nota',
  templateUrl: './ingresar-nota.component.html',
  styleUrls: ['./ingresar-nota.component.css']
})
export class IngresarNotaComponent implements OnInit {

  public formulario: FormGroup;
  
  constructor(public dialogRef: MatDialogRef<IngresarNotaComponent>) {}

  ngOnInit(): void {
    this.formulario = new FormGroup({
      nota: new FormControl('', [Validators.required])
    });
  }

  enviar(){
    this.dialogRef.close(this.formulario.controls['nota'].value);
  }
}
