import { CarreraDTO } from './carrera-dto';
import { PersonaDTO } from './persona-dto';
import { SedeDTO } from './sede-dto';

export class PostulanteDTO {
    public id:              number;
    public estado:          string;
    public img_ci:          string;
    public img_escolaridad: string;
    public img_carne_salud: string;
    public sede:            SedeDTO;
    public carrera:         CarreraDTO;
    public persona:         PersonaDTO;
}
