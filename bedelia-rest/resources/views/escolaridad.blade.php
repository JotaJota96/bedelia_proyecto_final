<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolaridad</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>

    <style>
        .titulo-1 .titulo-2 .titulo-3 .titulo-4 .titulo-5 .titulo-6 {
            font-weight: 500;
        }
        .titulo-1 {
            font-size: 2.25rem;
        }
        .titulo-2 {
            font-size: 2rem;
        }
        .titulo-3 {
            font-size: 1.75rem;
        }
        .titulo-4 {
            font-size: 1.5rem;
        }
        .titulo-5 {
            font-size: 1.25rem;
        }
        .titulo-6 {
            font-size: 1rem;
        }
        p, td{
            font-size: 0.8rem;
        }
        td, th{
            padding:    4px !important;
        }
        @page { margin: 100px 25px; }
        header { 
            position: fixed;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 50px;
            border-bottom: solid 1px rgb(163, 163, 163);
         }
        footer {
            position: fixed;
            bottom: -80px;
            left: 0px;
            right: 0px;
            height: 50px;
            border-top: solid 1px rgb(163, 163, 163);
        }
        .page-number:after {
            content: counter(page);
        }
        .no-break {
           page-break-inside: avoid;
        }
        hr{
            border-top: solid 1px rgb(219, 219, 219) !important;
        }
    </style>
</head>

<body >
    <!-- Inicio de cabecera de todas las páginas-->
    <header>
        <table class="w-100 mx-auto">
            <tr>
                <td class="small text-left">UTEC - Universidad Tecnológica</td>
                <td class="small text-right page-number ">Página: </td>
            </tr>
        </table>
    </header>
    <!-- Fin de cabecera de todas las páginas-->

    <!-- ---------------------------------------------------- -->

    <!-- Inicio de pie de todas las páginas-->
    <footer>
        <p class="my-1 text-center">
            Código de verificación: <b>{{ $codigoVerificacion }}</b>. (Válido hasta: <b>{{ $fechaValidez }}</b>)
        </p>
        <p class="my-1 text-center">
            Puede validar esta escolaridad en <a href="{{ $urlVerificar }}">{{ $urlVerificar }}</a>.
        </p>
    </footer>
    <!-- Fin de pie de todas las páginas-->

    <!-- ---------------------------------------------------- -->

    <!-- Inicio del contenido del documento-->
    <main>
        <div>
            <p class="titulo-1 text-center">
                UTEC - Universidad Tecnológica
            </p>
            <p class="titulo-3 text-center">
                Certificado de escolaridad
            </p>
            <p class="titulo-5 text-center">
                Resultados finales e intermedios
            </p>
            <p class="titulo-6 text-center">
                Fecha de emisión: {{ $fecha }}
            </p>
        </div>
        
        <div class="my-4">
            <table class="table">
                <tr>
                    <td>Cédula:</td>
                    <td>{{ $usuario->persona->cedula }}</td>
                </tr>
                <tr>
                    <td>Nombre:</td>
                    <td>{{ $usuario->persona->nombre }}</td>
                </tr>
                <tr>
                    <td>Apellido:</td>
                    <td>{{ $usuario->persona->apellido }}</td>
                </tr>
                <tr>
                    <td>Carrera:</td>
                    <td>{{ $carrera->nombre }}</td>
                </tr>
                <tr>
                    <td>Nota promedio:</td>
                    <td>{{ $nota_promedio }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <hr class="my-2">
    
        <div class="my-3">
            <p class="titulo-4 text-center">
                Progreso académico
            </p>
    
            @foreach ($semestres as $sem)
            <div class="no-break">
                <p class="titulo-5">
                    Semestre {{ $sem['numero'] }}
                </p>
    
                <table class="table">
                    <tr class="table-secondary">
                        <th class="pl-2">Curso</th>
                        <th class="pl-2">Tipo</th>
                        <th class="pl-2">Período</th>
                        <th class="pl-2">Nota</th>
                    </tr>
    
                    @foreach ($sem['detalle'] as $linea)
                    <?php
                    switch ($linea['tipo']) {
                        case 'LE':
                            $linea['tipo'] = "Curso";
                            break;
                        case 'EX':
                            $linea['tipo'] = "Exámen";
                            break;
                        default:
                            $linea['tipo'] = "-";
                            break;
                    }
                    ?>
                    <tr>
                        <td class="pl-2">{{ $linea['curso']->nombre }}</td>
                        <td class="pl-2">{{ $linea['tipo'] }}</td>
                        <td class="pl-2">{{ $linea['periodo'] }}</td>
                        <td class="pl-2">{{ $linea['nota'] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
        
        <hr>

        <div class="no-break mt-5 row ">
            <div class="mx-auto col-4">
                <p class="text-center titulo-6">
                    Escala de notas
                </p>
                <table class="table">
                    <tr>
                        <td class="pl-2">Mínima:</td>
                        <td class="pl-2">1.0</td>
                    </tr>
                    <tr>
                        <td class="pl-2">Aprobación:</td>
                        <td class="pl-2">3.0</td>
                    </tr>
                    <tr>
                        <td class="pl-2">Máxima:</td>
                        <td class="pl-2">5.0</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!--
        <div class="my-4">
            <p class="my-1 text-center">
                Código de verificación: <b>{{ $codigoVerificacion }}</b>. (Válido hasta: <b>{{ $fechaValidez }}</b>)
            </p>
            <p class="my-1 text-center">
                Puede validar esta escolaridad en <a href="{{ $urlVerificar }}">{{ $urlVerificar }}</a>.
            </p>
        </div>
        -->
    </main>
    <!-- Fin del contenido del documento-->

</body>

</html>