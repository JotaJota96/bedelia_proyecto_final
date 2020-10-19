# Envio de correos desde Lumen

## Configuración del proyecto

1. Agregar la dependencia al proyecto

    ```bash
    composer require "illuminate/mail:7.*"
    ```

2. Crear en el proyecto el archivo `config/mail.php` y colocarle el contenido que muestra el enlace: <https://github.com/laravel/laravel/blob/master/config/mail.php>

3. Agregar lo siguiente al archivo de ejemplo de configuracion de entorno `.env.example` (y al de verdad tambien `.env`)

    ```php
    MAIL_DRIVER=log
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=hello@example.com
    MAIL_FROM_NAME="Example app"
    ```

4. En el archivo `bootstrap/app.php` hacer lo siguiente:

    Descomentar la siguiente linea si está comentada:

    ```php
    $app->withFacades();
    ```

    Y agregarle `'Illuminate\Support\Facades\Mail' => 'Mail',`, debe quedar algo como:

    ```php
    $app->withFacades(true, [
        // .. 
        'Illuminate\Support\Facades\Mail' => 'Mail',
    ]);
    ```

    Agregar las siguientes lineas al final de la seccion `Register Service Providers`

    ```php
    $app->configure('mail');
    $app->register(Illuminate\Mail\MailServiceProvider::class);
    ```

Fuentes
<http://blog.tegimus.com/2018/08/11/enable-mail-on-lumen-framework/>

## Configurar y enviar email

Para cada tipo de correo que la aplicacion deba enviar, se debe crear un `mailable`, el cual contendrá la estructura base del correo a enviar.
Para crear el `mailable` (ej: `MiCorreo`) se debe ejecutar

```bash
php artisan make:mail MiCorreo
```

Esto creará el archivo `App/Mail/MiCorreo.php` que es una clase PHP.

Al final del archivo se encuentra el método `public function build()` el cual retornará una vista, que será el contenido del correo enviado.

Para crear la vista, se creará el archivo `resources/views/mi-correo.blade.php`.

**Notese que el nombre de la vista debe terminar en `.blade.php`**

El contenido de la vista puede ser por ejemplo:

```html
<p>Hola email</p>
<p>Este es un simple email de prueba</p>
```

Ahora en la función `build()` que se mencionó anteriormente, reemplazar su contenido por:

```php
return $this->view('mi-correo');
```

En el controlador donde se quiere enviar el email, se deben importar:

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\MiCorreo;
```

Para enviar el correo se debe hacer:

```php
Mail::to('destinatario@ejemplo.org')->send(new MiCorreo());
```

## Código final

### Controlador

```php

use App\Mail\MiCorreo;

class CorreosController extends Controller{

    // ...

    public function enviarCorreo(){
        // envia el correo solo si se habilita desde la configuracion
        if (env('MAIL_SEND')){
            try {
                $datos = [
                    "nombre" => "José",
                    "url" => env('APP_URL'),
                ];

                Mail::to('jjap96@gmail.com')->send(new MiCorreo($datos));
            } catch (\Exception $e) {
                return response()->json(
                    [
                        "message" => "No se pudo enviar el gorreo",
                        "details" => $e->getMessage()
                    ],
                    500
                );
            }
        }else{
            return response()->json("Correos desactivados", 200);
        }
        return response()->json("Correo enviado", 200);
    }
}
```

### Mailable

```php
// ...
class MiCorreo extends Mailable {
    // ...

    private $mailData = null;

    public function __construct($mailData = null) {
        $this->mailData = $mailData;
    }

    public function build() {
        if ($this->mailData == null){
            throw new Exception("No se especificaron datos para rellenar el correo");
        }
        return $this->subject('asunto')
            ->view('mi-correo')
            ->with($this->mailData);
    }
}
```

### Vista

```php
<h1>¡Hola {{ $nombre }}!</h1>
La documentación Swagger está disponible <a href="{{ $url }}">aquí</a>
```
