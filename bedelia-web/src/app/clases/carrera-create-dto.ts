import { AreaEstudioDTO } from './area-estudio-dto';
import { CursoDTO } from './curso-dto';
import { PreviaDTO } from './previa-dto';
import { SedeDTO } from './sede-dto';

export class CarreraCreateDTO {
    public id               :number;
    public nombre           :string;
    public descripcion      :string;
    public cant_semestres   :number;
    public areas_estudio    :AreaEstudioDTO[];
    public previas          :PreviaDTO[];
    public cursos           :CursoDTO[];
    public sedes            :SedeDTO[];
    
}
