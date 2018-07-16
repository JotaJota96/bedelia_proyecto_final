import { ListaAlumnosActaDTO } from './lista-alumnos-acta-dto'

export class ActaDTO {
    public id              : number
    public tipo            : string
    public fecha           : string
    public acta_confirmada : boolean
    public notas           : ListaAlumnosActaDTO[]
}