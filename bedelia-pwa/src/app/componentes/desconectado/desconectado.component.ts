import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ConnectionService } from 'ng-connection-service';

@Component({
  selector: 'app-desconectado',
  templateUrl: './desconectado.component.html',
  styleUrls: ['./desconectado.component.css']
})
export class DesconectadoComponent implements OnInit {

  constructor(private onlineService: ConnectionService, private router: Router) { }

  ngOnInit(): void {
    this.onlineService.monitor().subscribe(
      (conectado)=>{
        if(conectado){
          this.router.navigate(['/']);
          return;
        }
      }
    );
  }
}
