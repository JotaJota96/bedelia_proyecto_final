import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-usuarios',
  templateUrl: './usuarios.component.html',
  styleUrls: ['./usuarios.component.css']
})
export class UsuariosComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido', 'correo', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  usuariosDataSource = new MatTableDataSource([]);

  constructor(private _snackBar: MatSnackBar, protected usuServ: UsuariosService) { }

  ngOnInit(): void {
    // obtiene todos los usuarios y los carga en el DataSource de la tala
    this.usuServ.getAll().subscribe(
      (datos) => {
        this.usuariosDataSource.data = datos;
      }, (error) => {
        this.openSnackBar("No se pudieron cargar los usuarios de la base de dato");
      }
    );
    // Defino una funcion de filtrado personalizada
    this.usuariosDataSource.filterPredicate = (element: UsuarioDTO, filter: string): boolean => {
      // convierte el elemento a un string y revisa si contiene el texto del filtro
      let str = JSON.stringify(element.persona);
      return str.indexOf(filter) > -1;
    };
  }

  aplicarFiltro(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.usuariosDataSource.filter = filterValue.trim().toLowerCase();
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }

}
