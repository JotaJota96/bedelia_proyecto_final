import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ConnectionService } from 'ng-connection-service';
import { UsuariosService } from 'src/app/servis/usuarios.service';

@Component({
  selector: 'app-inicio',
  templateUrl: './inicio.component.html',
  styleUrls: ['./inicio.component.css']
})
export class InicioComponent implements OnInit {

  constructor(private onlineService: ConnectionService, private router: Router, protected usuServ: UsuariosService) { }

  ngOnInit(): void {
    if(navigator.onLine){
      if(!this.usuServ.isLogged()){
        this.router.navigate(['/login']);
        return;
      }
    }else{
      this.router.navigate(['/desconectado']);
      return;
    }
    
    this.onlineService.monitor().subscribe(
      (conectado)=>{
        if(!conectado){
          this.router.navigate(['/desconectado']);
          return;
        }
      }
    );
  }

}
