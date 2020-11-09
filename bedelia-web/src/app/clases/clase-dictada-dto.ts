import { ListaAlumnosDTO } from './lista-alumnos-dto'

export class ClaseDictadaDTO {
    public id :number
    public fecha :string
    public curso_id :number
    public edicion_curso_id :number
    public lista :ListaAlumnosDTO[]
}
/*
id	integer
readOnly: true
fecha	string
curso_id	integer
edicion_curso_id	integer
lista	[{...}]
}*/