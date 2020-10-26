import { CursoDTO } from './curso-dto';
import { SedeDTO } from './sede-dto';
import { UsuarioDTO } from './usuario-dto';

export class ExamenDTO {
    public id               :number;
    public fecha            :string;
    public acta_confirmada  :boolean;
    public habilitado       :number
    public sede             :SedeDTO;
    public curso            :CursoDTO;
    public docente          :UsuarioDTO;
}