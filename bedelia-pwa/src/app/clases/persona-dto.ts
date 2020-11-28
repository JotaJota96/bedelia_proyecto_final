import { DireccionDTO } from './direccion-dto';

export class PersonaDTO {
    public id:        number;
    public cedula:    string;
    public nombre:    string;
    public apellido:  string;
    public correo:    string;
    public fecha_nac: string;
    public sexo:      string;
    public direccion: DireccionDTO;
}
