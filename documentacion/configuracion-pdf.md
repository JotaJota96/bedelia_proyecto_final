# domPDF

## Instalacion

1. Ejecutar el siguiente comando

    ```php
    composer require barryvdh/laravel-dompdf
    ```

2. En el archivo `bootstrap/app.php` agregar las siguientes lineas:

    ```php
    $app->register(\Barryvdh\DomPDF\ServiceProvider::class);
    $app->configure('dompdf');
    ```

    Agregar la `Facade`

    ```php
    $app->withFacades(true, [
        // ...
        'Barryvdh\DomPDF\Facade' => 'PDF',
    ]);
    ```

3. En donde se quiera usar `PDF`, recordar agregar el siguiente `use`:

    ```php
    use Barryvdh\DomPDF\Facade as PDF;
    ```

## Generar PDF desde una vista

Generar PDF

```php
// genera PDF a partir de la vista 'escolaridad.blade.php'
$pdf->loadView('escolaridad');

// genera PDF a partir del código HTML
$pdf->loadHtml("<h1>hola mundo</h1>");
```

Convertir vista a `string`

```php
// renderiza la vista y la devuelve como `string`
$datos = [] // datos para mostrar en la vista
$html = view('escolaridad', $datos)->render();
```

Para retornar el PDF generado

```php
// Abrir el PDF en el navegador
return $pdf->stream();

// Abrir dialogo para descargar el PDF
return $pdf->download("escolaridad.pdf");

// Devuelve el PDF como un `string`
return $pdf->output("escolaridad.pdf");
```
