import { CarreraDTO } from './carrera-dto';
import { CursoDTO } from './curso-dto';
import { UsuarioDTO } from './usuario-dto';

export class EscolaridadDTO {
    public usuario : UsuarioDTO;
    public carrera : CarreraDTO;
    public nota_promedio : number;
    public semestres : [{
        numero: number,
        detalle:[{
            curso   :CursoDTO,
            tipo    :string,
            periodo :string,
            nota    :number,
        }];
    }];
}
