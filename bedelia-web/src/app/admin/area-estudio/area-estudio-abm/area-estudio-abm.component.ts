import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AreaEstudioDTO } from 'src/app/clases/area-estudio-dto';
import { AreaEstudioService } from 'src/app/servicios/area-estudio.service';

@Component({
  selector: 'app-area-estudio-abm',
  templateUrl: './area-estudio-abm.component.html',
  styleUrls: ['./area-estudio-abm.component.css']
})
export class AreaEstudioABMComponent implements OnInit {  
  public formulario: FormGroup;

  constructor(protected areaServ:AreaEstudioService,
    private router:Router) { }

  ngOnInit(): void {
    this.formulario = new FormGroup({
      area:    new FormControl('', [Validators.required])
      });
  }
  cargaDeDatos(area: AreaEstudioDTO){
    this.formulario.controls['area'].setValue(area.area);
  }

  agregar(){
    let area:AreaEstudioDTO = new AreaEstudioDTO();
    area.area = this.formulario.controls['area'].value;

    this.areaServ.create(area).subscribe(
      (datos)=>{
        this.router.navigate(['/admin/area']);
      },
      (error) =>{
        alert("Error");
      }
    );
  }
}