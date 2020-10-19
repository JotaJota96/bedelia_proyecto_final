import { CursoDTO } from './curso-dto';
import { SedeDTO } from './sede-dto';
import { UsuarioDTO } from './usuario-dto';

export class EdicionCursoDTO {
    public id               :number;
    public acta_confirmada  :boolean;
    public sede             :SedeDTO;
    public curso            :CursoDTO;
    public docente          :UsuarioDTO;
}