import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { DireccionDTO } from 'src/app/clases/direccion-dto';
import { SedeDTO} from 'src/app/clases/sede-dto';
import { SedesService } from 'src/app/servicios/sedes.service';

const DEPARTAMENTOS:string[] = [
  'Artigas',
  'Canelones',
  'Cerro Largo',
  'Colonia',
  'Durazno',
  'Flores',
  'Florida',
  'Lavalleja',
  'Maldonado',
  'Montevideo',
  'Paysandú',
  'Río Negro',
  'Rivera',
  'Rocha',
  'Salto',
  'San José',
  'Soriano',
  'Tacuarembó',
  'Treinta y Tres',
]

@Component({
  selector: 'app-sede-abm',
  templateUrl: './sede-abm.component.html',
  styleUrls: ['./sede-abm.component.css']
})
export class SedeABMComponent implements OnInit {
  listaDepartamentos:string[] = DEPARTAMENTOS;
  soloLectura:boolean = false;

  public formulario: FormGroup;

  constructor(protected sedeServ:SedesService,
    private router:Router, private rutaActiva: ActivatedRoute) { }

  ngOnInit(): void {
    let parametrosId:number = this.rutaActiva.snapshot.params.id;

    if(parametrosId != undefined){
      this.soloLectura = true
      let usu = new SedeDTO();
      this.sedeServ.get(parametrosId).subscribe(
        (datos)=>{
          this.cargaDeDatos(datos);
        },
        (error) =>{
          alert("Error");
        }
      );
    }

    this.formulario = new FormGroup({
      // sede
      telefono:    new FormControl('', [Validators.required]),
      // direccion
      departamento: new FormControl('', [Validators.required]),
      ciudad:       new FormControl('', [Validators.required]),
      calle:        new FormControl('', [Validators.required]),
      numero:       new FormControl('', [Validators.required]),
      });
  }
  cargaDeDatos(sede: SedeDTO){
    // sede
    this.formulario.controls['telefono'].setValue(sede.telefono);
    // direccion
    this.formulario.controls['departamento'].setValue(sede.direccion.departamento);
    this.formulario.controls['ciudad'].setValue(sede.direccion.ciudad);
    this.formulario.controls['calle'].setValue(sede.direccion.calle);
    this.formulario.controls['numero'].setValue(sede.direccion.numero);
    
  }
  vaciarDatos(){
    // sede
    this.formulario.controls['telefono'].setValue("");
    // direccion
    this.formulario.controls['departamento'].setValue("");
    this.formulario.controls['ciudad'].setValue("");
    this.formulario.controls['calle'].setValue("");
    this.formulario.controls['numero'].setValue("");
  }

  agregar(){
    let sede:SedeDTO = new SedeDTO();
    sede.direccion = new DireccionDTO();
    
    sede.telefono               = this.formulario.controls['telefono'].value;
    
    sede.direccion.departamento = this.formulario.controls['departamento'].value;
    sede.direccion.ciudad       = this.formulario.controls['ciudad'].value;
    sede.direccion.calle        = this.formulario.controls['calle'].value;
    sede.direccion.numero       = this.formulario.controls['numero'].value;


    this.sedeServ.create(sede).subscribe(
      (datos)=>{
        alert("Hecho");
        this.router.navigate(['/admin/sede']);
      },
      (error) =>{
        alert("Error");
      }
    );
  }
}
