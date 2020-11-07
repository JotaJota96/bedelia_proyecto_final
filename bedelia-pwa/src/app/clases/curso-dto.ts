import { AreaEstudioDTO } from './area-estudio-dto';
import { TipoCursoDTO } from './tipo-curso-dto';

export class CursoDTO {
    public id                   :number;
    public nombre               :string;
    public descripcion          :string;
    public max_inasistencias    :number;
    public cant_creditos        :number;
    public cant_clases          :number;
    public semestre             :number;
    public optativo             :boolean;
    
    public area_estudio         :AreaEstudioDTO;
    public tipo_curso           :TipoCursoDTO;    
}
