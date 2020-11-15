import { formatDate } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { AnioLectivoDTO } from 'src/app/clases/anio-lectivo-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AnioLectivoService } from 'src/app/servicios/anio-lectivo.service';

@Component({
  selector: 'app-anio-lectivo-abm',
  templateUrl: './anio-lectivo-abm.component.html',
  styleUrls: ['./anio-lectivo-abm.component.css']
})
export class AnioLectivoABMComponent implements OnInit {
  soloLectura: boolean = true;
  periodoLec: AnioLectivoDTO;

  public formulario: FormGroup;

  constructor(protected periodoServ: AnioLectivoService, private _snackBar: MatSnackBar) { }

  ngOnInit(): void {

    this.formulario = new FormGroup({
      ini_1er_per_insc_exam: new FormControl('', [Validators.required]),
      fin_1er_per_insc_exam: new FormControl('', [Validators.required]),

      ini_1er_per_exam: new FormControl('', [Validators.required]),
      fin_1er_per_exam: new FormControl('', [Validators.required]),

      ini_1er_per_insc_lect: new FormControl('', [Validators.required]),
      fin_1er_per_insc_lect: new FormControl('', [Validators.required]),

      ini_1er_per_lect: new FormControl('', [Validators.required]),
      fin_1er_per_lect: new FormControl('', [Validators.required]),

      ini_2do_per_insc_exam: new FormControl('', [Validators.required]),
      fin_2do_per_insc_exam: new FormControl('', [Validators.required]),

      ini_2do_per_exam: new FormControl('', [Validators.required]),
      fin_2do_per_exam: new FormControl('', [Validators.required]),

      ini_2do_per_insc_lect: new FormControl('', [Validators.required]),
      fin_2do_per_insc_lect: new FormControl('', [Validators.required]),

      ini_2do_per_lect: new FormControl('', [Validators.required]),
      fin_2do_per_lect: new FormControl('', [Validators.required]),

      ini_3er_per_insc_exam: new FormControl('', [Validators.required]),
      fin_3er_per_insc_exam: new FormControl('', [Validators.required]),

      ini_3er_per_exam: new FormControl('', [Validators.required]),
      fin_3er_per_exam: new FormControl('', [Validators.required]),
    });

    this.periodoServ.get().subscribe(
      (datos) => {
        this.periodoLec = datos;
        this.cargaDeDatos(datos);
      },
      (error) => {
        openSnackBar(this._snackBar, "No se pudo cargar los años lectivos desde la base de dato");
      }
    );
  }

  volver() {
    this.soloLectura = true;
    this.cargaDeDatos(this.periodoLec);
  }

  crearPeriodo() {
    this.soloLectura = false;
    this.vaciarDatos();
  }

  cargaDeDatos(anio: AnioLectivoDTO) {
    this.formulario.controls['ini_1er_per_insc_exam'].setValue(anio.ini_1er_per_insc_exam);
    this.formulario.controls['fin_1er_per_insc_exam'].setValue(anio.fin_1er_per_insc_exam);

    this.formulario.controls['ini_1er_per_exam'].setValue(anio.ini_1er_per_exam);
    this.formulario.controls['fin_1er_per_exam'].setValue(anio.fin_1er_per_exam);

    this.formulario.controls['ini_1er_per_insc_lect'].setValue(anio.ini_1er_per_insc_lect);
    this.formulario.controls['fin_1er_per_insc_lect'].setValue(anio.fin_1er_per_insc_lect);

    this.formulario.controls['ini_1er_per_lect'].setValue(anio.ini_1er_per_lect);
    this.formulario.controls['fin_1er_per_lect'].setValue(anio.fin_1er_per_lect);

    this.formulario.controls['ini_2do_per_insc_exam'].setValue(anio.ini_2do_per_insc_exam);
    this.formulario.controls['fin_2do_per_insc_exam'].setValue(anio.fin_2do_per_insc_exam);

    this.formulario.controls['ini_2do_per_exam'].setValue(anio.ini_2do_per_exam);
    this.formulario.controls['fin_2do_per_exam'].setValue(anio.fin_2do_per_exam);

    this.formulario.controls['ini_2do_per_insc_lect'].setValue(anio.ini_2do_per_insc_lect);
    this.formulario.controls['fin_2do_per_insc_lect'].setValue(anio.fin_2do_per_insc_lect);

    this.formulario.controls['ini_2do_per_lect'].setValue(anio.ini_2do_per_lect);
    this.formulario.controls['fin_2do_per_lect'].setValue(anio.fin_2do_per_lect);

    this.formulario.controls['ini_3er_per_insc_exam'].setValue(anio.ini_3er_per_insc_exam);
    this.formulario.controls['fin_3er_per_insc_exam'].setValue(anio.fin_3er_per_insc_exam);

    this.formulario.controls['ini_3er_per_exam'].setValue(anio.ini_3er_per_exam);
    this.formulario.controls['fin_3er_per_exam'].setValue(anio.fin_3er_per_exam);
  }

  vaciarDatos() {
    this.formulario.controls['ini_1er_per_insc_exam'].setValue("");
    this.formulario.controls['fin_1er_per_insc_exam'].setValue("");

    this.formulario.controls['ini_1er_per_exam'].setValue("");
    this.formulario.controls['fin_1er_per_exam'].setValue("");

    this.formulario.controls['ini_1er_per_insc_lect'].setValue("");
    this.formulario.controls['fin_1er_per_insc_lect'].setValue("");

    this.formulario.controls['ini_1er_per_lect'].setValue("");
    this.formulario.controls['fin_1er_per_lect'].setValue("");

    this.formulario.controls['ini_2do_per_insc_exam'].setValue("");
    this.formulario.controls['fin_2do_per_insc_exam'].setValue("");

    this.formulario.controls['ini_2do_per_exam'].setValue("");
    this.formulario.controls['fin_2do_per_exam'].setValue("");

    this.formulario.controls['ini_2do_per_insc_lect'].setValue("");
    this.formulario.controls['fin_2do_per_insc_lect'].setValue("");

    this.formulario.controls['ini_2do_per_lect'].setValue("");
    this.formulario.controls['fin_2do_per_lect'].setValue("");

    this.formulario.controls['ini_3er_per_insc_exam'].setValue("");
    this.formulario.controls['fin_3er_per_insc_exam'].setValue("");

    this.formulario.controls['ini_3er_per_exam'].setValue("");
    this.formulario.controls['fin_3er_per_exam'].setValue("");
  }

  agregar() {
    let anio: AnioLectivoDTO = new AnioLectivoDTO();

    if (this.validarFechas() == false){
      openSnackBar(this._snackBar, "Uno o más periodos ingresados no son válidos");
      return;
    }

    anio.ini_1er_per_insc_exam = this.formulario.controls['ini_1er_per_insc_exam'].value;
    anio.fin_1er_per_insc_exam = this.formulario.controls['fin_1er_per_insc_exam'].value;
    anio.ini_1er_per_exam = this.formulario.controls['ini_1er_per_exam'].value;
    anio.fin_1er_per_exam = this.formulario.controls['fin_1er_per_exam'].value;
    anio.ini_1er_per_insc_lect = this.formulario.controls['ini_1er_per_insc_lect'].value;
    anio.fin_1er_per_insc_lect = this.formulario.controls['fin_1er_per_insc_lect'].value;
    anio.ini_1er_per_lect = this.formulario.controls['ini_1er_per_lect'].value;
    anio.fin_1er_per_lect = this.formulario.controls['fin_1er_per_lect'].value;
    anio.ini_2do_per_insc_exam = this.formulario.controls['ini_2do_per_insc_exam'].value;
    anio.fin_2do_per_insc_exam = this.formulario.controls['fin_2do_per_insc_exam'].value;
    anio.ini_2do_per_exam = this.formulario.controls['ini_2do_per_exam'].value;
    anio.fin_2do_per_exam = this.formulario.controls['fin_2do_per_exam'].value;
    anio.ini_2do_per_insc_lect = this.formulario.controls['ini_2do_per_insc_lect'].value;
    anio.fin_2do_per_insc_lect = this.formulario.controls['fin_2do_per_insc_lect'].value;
    anio.ini_2do_per_lect = this.formulario.controls['ini_2do_per_lect'].value;
    anio.fin_2do_per_lect = this.formulario.controls['fin_2do_per_lect'].value;
    anio.ini_3er_per_insc_exam = this.formulario.controls['ini_3er_per_insc_exam'].value;
    anio.fin_3er_per_insc_exam = this.formulario.controls['fin_3er_per_insc_exam'].value;
    anio.ini_3er_per_exam = this.formulario.controls['ini_3er_per_exam'].value;
    anio.fin_3er_per_exam = this.formulario.controls['fin_3er_per_exam'].value;

    anio.ini_1er_per_insc_exam = formatDate(anio.ini_1er_per_insc_exam, 'yyyy-MM-dd', 'en-US');
    anio.fin_1er_per_insc_exam = formatDate(anio.fin_1er_per_insc_exam, 'yyyy-MM-dd', 'en-US');
    anio.ini_1er_per_exam = formatDate(anio.ini_1er_per_exam, 'yyyy-MM-dd', 'en-US');
    anio.fin_1er_per_exam = formatDate(anio.fin_1er_per_exam, 'yyyy-MM-dd', 'en-US');
    anio.ini_1er_per_insc_lect = formatDate(anio.ini_1er_per_insc_lect, 'yyyy-MM-dd', 'en-US');
    anio.fin_1er_per_insc_lect = formatDate(anio.fin_1er_per_insc_lect, 'yyyy-MM-dd', 'en-US');
    anio.ini_1er_per_lect = formatDate(anio.ini_1er_per_lect, 'yyyy-MM-dd', 'en-US');
    anio.fin_1er_per_lect = formatDate(anio.fin_1er_per_lect, 'yyyy-MM-dd', 'en-US');
    anio.ini_2do_per_insc_exam = formatDate(anio.ini_2do_per_insc_exam, 'yyyy-MM-dd', 'en-US');
    anio.fin_2do_per_insc_exam = formatDate(anio.fin_2do_per_insc_exam, 'yyyy-MM-dd', 'en-US');
    anio.ini_2do_per_exam = formatDate(anio.ini_2do_per_exam, 'yyyy-MM-dd', 'en-US');
    anio.fin_2do_per_exam = formatDate(anio.fin_2do_per_exam, 'yyyy-MM-dd', 'en-US');
    anio.ini_2do_per_insc_lect = formatDate(anio.ini_2do_per_insc_lect, 'yyyy-MM-dd', 'en-US');
    anio.fin_2do_per_insc_lect = formatDate(anio.fin_2do_per_insc_lect, 'yyyy-MM-dd', 'en-US');
    anio.ini_2do_per_lect = formatDate(anio.ini_2do_per_lect, 'yyyy-MM-dd', 'en-US');
    anio.fin_2do_per_lect = formatDate(anio.fin_2do_per_lect, 'yyyy-MM-dd', 'en-US');
    anio.ini_3er_per_insc_exam = formatDate(anio.ini_3er_per_insc_exam, 'yyyy-MM-dd', 'en-US');
    anio.fin_3er_per_insc_exam = formatDate(anio.fin_3er_per_insc_exam, 'yyyy-MM-dd', 'en-US');
    anio.ini_3er_per_exam = formatDate(anio.ini_3er_per_exam, 'yyyy-MM-dd', 'en-US');
    anio.fin_3er_per_exam = formatDate(anio.fin_3er_per_exam, 'yyyy-MM-dd', 'en-US');

    console.log(anio);
    this.periodoServ.create(anio).subscribe(
      (datos) => {
        this.soloLectura = true;
      },
      (error) => {
        openSnackBar(this._snackBar, "Error al crear el año lectivo");
      }
    );
  }

  validarFechas(): boolean{
    console.log("Vino a validar");

    let ini_1er_per_insc_exam = Date.parse(this.formulario.controls['ini_1er_per_insc_exam'].value);
    let fin_1er_per_insc_exam = Date.parse(this.formulario.controls['fin_1er_per_insc_exam'].value);
    let ini_1er_per_exam      = Date.parse(this.formulario.controls['ini_1er_per_exam'].value);
    let fin_1er_per_exam      = Date.parse(this.formulario.controls['fin_1er_per_exam'].value);
    let ini_1er_per_insc_lect = Date.parse(this.formulario.controls['ini_1er_per_insc_lect'].value);
    let fin_1er_per_insc_lect = Date.parse(this.formulario.controls['fin_1er_per_insc_lect'].value);
    let ini_1er_per_lect      = Date.parse(this.formulario.controls['ini_1er_per_lect'].value);
    let fin_1er_per_lect      = Date.parse(this.formulario.controls['fin_1er_per_lect'].value);
    let ini_2do_per_insc_exam = Date.parse(this.formulario.controls['ini_2do_per_insc_exam'].value);
    let fin_2do_per_insc_exam = Date.parse(this.formulario.controls['fin_2do_per_insc_exam'].value);
    let ini_2do_per_exam      = Date.parse(this.formulario.controls['ini_2do_per_exam'].value);
    let fin_2do_per_exam      = Date.parse(this.formulario.controls['fin_2do_per_exam'].value);
    let ini_2do_per_insc_lect = Date.parse(this.formulario.controls['ini_2do_per_insc_lect'].value);
    let fin_2do_per_insc_lect = Date.parse(this.formulario.controls['fin_2do_per_insc_lect'].value);
    let ini_2do_per_lect      = Date.parse(this.formulario.controls['ini_2do_per_lect'].value);
    let fin_2do_per_lect      = Date.parse(this.formulario.controls['fin_2do_per_lect'].value);
    let ini_3er_per_insc_exam = Date.parse(this.formulario.controls['ini_3er_per_insc_exam'].value);
    let fin_3er_per_insc_exam = Date.parse(this.formulario.controls['fin_3er_per_insc_exam'].value);
    let ini_3er_per_exam      = Date.parse(this.formulario.controls['ini_3er_per_exam'].value);
    let fin_3er_per_exam      = Date.parse(this.formulario.controls['fin_3er_per_exam'].value);

    console.log("Vino a 2");

    if      (ini_1er_per_insc_exam > fin_1er_per_insc_exam) return false;
    else if (ini_1er_per_exam      > fin_1er_per_exam)      return false;
    else if (ini_1er_per_insc_lect > fin_1er_per_insc_lect) return false;
    else if (ini_1er_per_lect      > fin_1er_per_lect)      return false;
    else if (ini_2do_per_insc_exam > fin_2do_per_insc_exam) return false;
    else if (ini_2do_per_exam      > fin_2do_per_exam)      return false;
    else if (ini_2do_per_insc_lect > fin_2do_per_insc_lect) return false;
    else if (ini_2do_per_lect      > fin_2do_per_lect)      return false;
    else if (ini_3er_per_insc_exam > fin_3er_per_insc_exam) return false;
    else if (ini_3er_per_exam      > fin_3er_per_exam)      return false;
    console.log("Vino a 3");
    return true;
  }
}
