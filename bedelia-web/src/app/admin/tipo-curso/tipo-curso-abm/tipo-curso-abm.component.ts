import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { TipoCursoDTO } from 'src/app/clases/tipo-curso-dto';
import { TipoCursoService } from 'src/app/servicios/tipo-curso.service';

@Component({
  selector: 'app-tipo-curso-abm',
  templateUrl: './tipo-curso-abm.component.html',
  styleUrls: ['./tipo-curso-abm.component.css']
})
export class TipoCursoABMComponent implements OnInit {
  public formulario: FormGroup;

  constructor(private _snackBar: MatSnackBar, protected tipoServ: TipoCursoService,
    private dialogRef: MatDialogRef<TipoCursoABMComponent>,
    private router: Router) { }

  ngOnInit(): void {
    this.formulario = new FormGroup({
      tipo: new FormControl('', [Validators.required])
    });
  }

  cargaDeDatos(tipo: TipoCursoDTO) {
    this.formulario.controls['tipo'].setValue(tipo.tipo);
  }

  agregar() {
    let tipo: TipoCursoDTO = new TipoCursoDTO();
    tipo.tipo = this.formulario.controls['tipo'].value;

    this.tipoServ.create(tipo).subscribe(
      (datos) => {
        this.router.navigate(['/admin/tipo']);
      },
      (error) => {
        this.openSnackBar("No se pudo crear los tipos de curso");
      }
    );
    this.dialogRef.close();
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
