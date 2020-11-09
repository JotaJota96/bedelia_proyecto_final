import { CursoDTO } from './curso-dto'
import { ListaAlumnosActaDTO } from './lista-alumnos-acta-dto'

export class ActaDTO {
    public id              : number
    public tipo            : string
    public fecha           : string
    public acta_confirmada : boolean
    public curso           : CursoDTO
    public notas           : ListaAlumnosActaDTO[]
}