import { PersonaDTO } from './persona-dto';

export class UsuarioDTO {
    id:          number;
    contrasenia: string;
    roles:       string[];
    persona:     PersonaDTO;
}