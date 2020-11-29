import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { element } from 'protractor';
import { AreaEstudioDTO } from 'src/app/clases/area-estudio-dto';
import { CarreraCreateDTO } from 'src/app/clases/carrera-create-dto';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { PreviaDTO } from 'src/app/clases/previa-dto';
import { SedeDTO } from 'src/app/clases/sede-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AreaEstudioService } from 'src/app/servicios/area-estudio.service';
import { CarreraService } from 'src/app/servicios/carrera.service';
import { CursoService } from 'src/app/servicios/curso.service';
import { SedesService } from 'src/app/servicios/sedes.service';
import { ModalPreviaComponent } from './modal-previa/modal-previa.component';

export interface Isemestre {
  clave: number;
  cursos: CursoDTO[];
}

@Component({
  selector: 'app-carrera-abm',
  templateUrl: './carrera-abm.component.html',
  styleUrls: ['./carrera-abm.component.css']
})
export class CarreraABMComponent implements OnInit {

  listaSedeSeleccionada: SedeDTO[] = [];
  listaAreaSeleccionada: AreaEstudioDTO[] = [];
  listaTodosCursoSeleccionados: CursoDTO[] = [];
  listaPrevias: PreviaDTO[] = []
  contadorSemestres: number = 1;

  listaCursoSeleccionados: CursoDTO[] = [];
  listaSedes: SedeDTO[] = [];
  listaAreasEstudio: AreaEstudioDTO[];
  listaCurso: CursoDTO[] = [];
  listaSemestre: Isemestre[] = [];

  previaOcultar: boolean = false;

  public formulario: FormGroup;
  public formularioSede: FormGroup;
  public formularioArea: FormGroup;
  public formularioCurso: FormGroup;

  constructor(private _snackBar: MatSnackBar, private router:Router, public dialog: MatDialog, protected carreraServ: CarreraService, protected sedeServ: SedesService, protected cursoServ: CursoService,
    protected areaServ: AreaEstudioService) { }

  ngOnInit(): void {

    this.areaServ.getAll().subscribe(
      (datos) => {
        this.listaAreasEstudio = datos;
      },(erro)=>{
        openSnackBar(this._snackBar, "Error al cargar las áreas de estudio");
      }
    );

    this.sedeServ.getAll().subscribe(
      (datos) => {
        this.listaSedes = datos;
      },(error)=>{
        openSnackBar(this._snackBar, "Error al cargar las sedes");
      }
    );
    
    // Los cursos ya no se cargan al inicio
    // this.cursoServ.getAll().subscribe(
    //   (datos) => {
    //     this.listaCurso = datos;
    //   },(error)=>{
    //     openSnackBar(this._snackBar, "Error al cargar los cursos");
    //   }
    // );

    this.formulario = new FormGroup({
      nombre: new FormControl('', [Validators.required]),
      descripcion: new FormControl('', [Validators.required]),
    });

    this.formularioArea = new FormGroup({
      area: new FormControl('', [Validators.required]),
      creditos: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
    });

    this.formularioSede = new FormGroup({
      sede: new FormControl('', [Validators.required]),
    });

    this.formularioCurso = new FormGroup({
      curso: new FormControl('', [Validators.required]),
      optativo: new FormControl('', [Validators.required]),
    });
  }

  asignarSedes() {
    if (this.listaSedeSeleccionada.includes(this.formularioSede.controls['sede'].value)) {
      return;
    }

    this.listaSedeSeleccionada.push(this.formularioSede.controls['sede'].value);
    this.formularioSede.controls['sede'].setValue(undefined);
  }

  quitarSede(sede:SedeDTO){
    const index = this.listaSedeSeleccionada.indexOf(sede);

    if (index >= 0) {
      this.listaSedeSeleccionada.splice(index, 1);
    }
  }

  asignarArea() {
    if (this.listaAreaSeleccionada.includes(this.formularioArea.controls['area'].value)) {
      return;
    }
    let creditos: number = this.formularioArea.controls['creditos'].value

    this.formularioArea.controls['area'].value.creditos = creditos;
    let idArea = this.formularioArea.controls['area'].value.id;
    this.listaAreaSeleccionada.push(this.formularioArea.controls['area'].value);

    this.formularioArea.reset();
    this.formularioCurso.reset();

    // actualizo la lista de cursos para incluir los de la nueva area de estudio
    this.areaServ.getCursos(idArea).subscribe(
      (datos) => {
        datos.forEach(element => {
          this.listaCurso.push(element);
        });
      },(error)=>{
        openSnackBar(this._snackBar, "Error al cargar los cursos");
      }
    );
  }

  quitarArea(area:AreaEstudioDTO){
    const index = this.listaAreaSeleccionada.indexOf(area);

    if (index < 0) return;

    // verifico si ya se agregó algun curso que pertenece a esa area de estudio
    // si encuentro alguno muestro un error
    let cancelar = this.areaDeEstudioEnUso(area);

    if (cancelar){
      openSnackBar(this._snackBar, "No se puede quitar el área de estudio, ya se han agregado cursos que pertenecen a ella");
    }else{
      // ahora hay que remover los cursos que pertenecian a esa area del desplegable de cursos
      this.listaCurso = this.listaCurso.filter(function(value, index, arr){ 
        return value.area_estudio.id != area.id;
      });

      this.listaAreaSeleccionada.splice(index, 1);
    }
  }

  asignarCurso() {
    if (this.listaTodosCursoSeleccionados.includes(this.formularioCurso.controls['curso'].value)) {
      return;
    }
    if ( ! this.listaCurso.includes(this.formularioCurso.controls['curso'].value)){
      return;
    }

    (this.formularioCurso.controls['curso'].value).semestre = this.contadorSemestres;

    let optativo:boolean = this.formularioCurso.controls['optativo'].value;
    (this.formularioCurso.controls['curso'].value).optativo = optativo;
    
    this.listaTodosCursoSeleccionados.push(this.formularioCurso.controls['curso'].value);
    this.listaCursoSeleccionados.push(this.formularioCurso.controls['curso'].value);
    this.formularioCurso.reset();
  }
  
  quitarCurso(curso:CursoDTO){
    let index = -1;

    index = this.listaCursoSeleccionados.indexOf(curso);
    if (index >= 0) this.listaCursoSeleccionados.splice(index, 1);
    
    index = this.listaTodosCursoSeleccionados.indexOf(curso);
    if (index >= 0) this.listaTodosCursoSeleccionados.splice(index, 1);
  }


  crearSemestre() {
    this.listaSemestre.push({
      clave: this.contadorSemestres,
      cursos: this.listaCursoSeleccionados
    });

    this.listaCursoSeleccionados.forEach(element => {
      this.listaCurso.splice(this.listaCurso.indexOf(element), 1);
    });

    this.contadorSemestres++;
    this.listaCursoSeleccionados = [];
    this.formularioCurso.controls['curso'].setValue(undefined);
  }

  // ¡wii! Al fin una recursiva :-)
  quitarSemestre(claveSemestre:number, pedirConfirmacion:boolean = false){
    if (this.contadorSemestres == claveSemestre) return;

    // si hay que pedir confirmacion
    if (pedirConfirmacion){
      // ...
      // si no se confirma, agregar un return;
    }

    // llamo recursividad para que elimine los siguientes semestres comenzando por el ultimo
    this.quitarSemestre(claveSemestre+1);

    // elimino el semestre especificado por clave y todas sus cosas relacionadas
    // asumiendo que es el ultimo

    // busco el semestre especificado y recorro sus cursos
    let semestre = this.listaSemestre.find(element => element.clave == claveSemestre);
    // elimino de la lista de cursos y de previas, las cosas relacinadas con los cursos del semestre a eliminar
    semestre.cursos.forEach(c => {
      this.listaTodosCursoSeleccionados = this.listaTodosCursoSeleccionados.filter(cs => cs.id != c.id);
      this.listaPrevias = this.listaPrevias.filter(p => p.curso_id != c.id);

      // y tambien hay que agregar los cursos sel semestre quitado a la lista de cursos para que puedan volver a ser seleccionados
      this.listaCurso.push(c);
    });

    // quito el semestre y actualizo el contador
    this.listaSemestre.pop();
    this.contadorSemestres--;
  }

  cargarListaPrevia(curso: CursoDTO, claveSemestre: number) {
    // si se le quieren establecer las previas al primer semestre, da error
    if (claveSemestre == 1) {
      openSnackBar(this._snackBar, "No se puede agregar previas a las materias de primer semestre");
      return;
    }

    // lista de cursos de los semestres anteriores al seleccionado
    let listaCursoPrevias: CursoDTO[] = [];
    // id del curso al que se le estableceran las previas
    let idCursoPrevia: number = curso.id;
    // coleccion de previas generadas
    let listaPrevia:PreviaDTO[] = [];

    // se obtienen los cursos dictados en los semestres anteriores
    this.listaSemestre.forEach(element => {
      if (element.clave < claveSemestre) {
        element.cursos.forEach(cursos => {
          listaCursoPrevias.push(cursos);
        });
      }
    });
    
    // obtengo las previas que ya habian sido definidas anteriormente (para comparar y que no se agregen repetidas)
    this.listaPrevias.forEach(element => {
      if (element.curso_id == curso.id){
        listaPrevia.push(element);
      }
    });

    // abre el dialogo pasandole la informacion que necesita
    const dialogRef = this.dialog.open(ModalPreviaComponent, {
      data: {
        curso: curso, // curso al que se le definiran las previas
        listaCursos: listaCursoPrevias, // lista de cursos que se pueden seleccionar
        listaPrevia: listaPrevia // lista de previas definidas anteriormente
      }
    });

    // cuando el dialogo se cierra
    dialogRef.afterClosed().subscribe(result => {
      // si no se devuelve nada, es que se cerro
      if (result == undefined) return;

      // saco todas las previas asociadas al curso para luego volverlas a agregar
      // (saco todas por si se decide eliminar alguna)
      this.listaPrevias = this.listaPrevias.filter(function(value, index, arr){ 
        return value.curso_id != curso.id;
      });

      // recorre la lista de previas devuelta y agrego todas
      result.listaPrevia.forEach(element => {
        this.listaPrevias.push(element);
      });
    });
  }

  validarForm():boolean{
    return this.formulario.valid 
      && this.listaSemestre.length > 0 
      && this.listaSedeSeleccionada.length > 0  
      &&  this.listaAreaSeleccionada.length > 0;
  }

  crear() {
    let vsdc = this.verificarSumaDeCreditos();
    if (vsdc != true ){
      openSnackBar(this._snackBar, "El área de estudio '" + vsdc + "' requiere mas créditos que los entregados por los cursos de la carrera");
      return;
    }
    let carrera: CarreraCreateDTO = new CarreraCreateDTO();
    carrera.nombre = this.formulario.controls['nombre'].value;
    carrera.descripcion = this.formulario.controls['descripcion'].value;
    carrera.cant_semestres = this.contadorSemestres - 1

    carrera.sedes = this.listaSedeSeleccionada;
    carrera.areas_estudio = this.listaAreaSeleccionada;
    carrera.cursos = this.listaTodosCursoSeleccionados;
    carrera.previas = this.listaPrevias;

    console.log(carrera);
    return;
    this.carreraServ.create(carrera).subscribe(
      (datos) => {
        this.router.navigate(['/admin/carrera']);
      },
      (error) => {
        openSnackBar(this._snackBar, "No se pudo crear la carrera");
      }
    );
  }
  
  verificarSumaDeCreditos(){
    let ret = undefined;
    this.listaAreaSeleccionada.forEach(a => {
      let suma:number = 0;
      this.listaTodosCursoSeleccionados.forEach(c => {
        if (c.area_estudio.id == a.id) {
          suma += c.cant_creditos;
        }
      });
      if (a.creditos > suma){
        ret = a.area;
      }
    });
    if (ret == undefined){
      return true;
    }else{
      return ret;
    }
  }

  areaDeEstudioEnUso(area:AreaEstudioDTO):boolean{
    // devuelve si ya se agregó algun curso que pertenece a esa area de estudio

    // reviso en los cursos sin agregar a semestre
    let usadoEnListaCursoSeleccionados = this.listaCursoSeleccionados.some(elem => elem.area_estudio.id == area.id);
    // reviso en los cursos ya agregados a algun semestre
    let usadoEnSemestre = this.listaSemestre.some(sem => sem.cursos.some(cur => cur.area_estudio.id == area.id));

    return usadoEnListaCursoSeleccionados || usadoEnSemestre;
  }

}
