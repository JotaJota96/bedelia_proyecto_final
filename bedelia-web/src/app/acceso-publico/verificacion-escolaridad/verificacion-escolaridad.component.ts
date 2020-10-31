import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-verificacion-escolaridad',
  templateUrl: './verificacion-escolaridad.component.html',
  styleUrls: ['./verificacion-escolaridad.component.css']
})
export class VerificacionEscolaridadComponent implements OnInit {

  public formulario: FormGroup;
  constructor() { }

  ngOnInit(): void {
    this.formulario = new FormGroup({
      codigo: new FormControl('', [Validators.required]),
      captcha: new FormControl('', [Validators.required]),
    });
  }

}
