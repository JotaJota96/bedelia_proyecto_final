# Instrucciones

## Requisitos

- **git:** 2.25.1
- **Docker:** 19.03.12, build 48a66213fe
- **Node:** 12.18.3
- **npm:** 6.14.6
- **Angular CLI:** 10.1.0
- **php:** 7.4.3
- **composer:** 1.10.10

## Clonar repositorio e instalar dependencias

1. Clonar el repositorio desde <https://github.com/JotaJota96/bedelia_proyecto_final>

2. Posicionarse dentro de la carpeta `bedelia-web` e instalar sus dependencias

    ```bash
    cd bedelia-web
    npm install
    ```

3. Posicionarse dentro de la carpeta `bedelia-pwa` e instalar sus dependencias

    ```bash
    cd bedelia-pwa
    npm install
    ```

4. Posicionarse dentro de la carpeta `bedelia-rest` e instalar sus dependencias

    ```bash
    cd bedelia-rest
    composer install
    ```

## Configurar y compilar para producción

1. Configurar la API REST

    1. Posicionarse dentro de la carpeta `bedelia-rest`
    2. Crear el archivo `.env` y colocarle el contenido del archivo `.env.example.prod`
    3. Revisar el valor de las siguientes variables:

        - APP_KEY: Clave para encriptación
        - APP_URL: URL donde se encuentra publicada la API

        - WEB_URL: URL del sitio web
        - WEB_URL_VERIFICAR: URL de la pagina para verificar la escolaridad

        - DB_USERNAME: Nombre de usuario de la base de datos
        - DB_PASSWORD: Contraseña del usuario de la base de datos

        - MAIL_SEND: Habilita (true) o deshabilita (false) el envío de mails
        - MAIL_USERNAME: Dirección de correo desde la que se enviarán los mails
        - MAIL_PASSWORD: Contraseña del correo desde la que se enviarán los mails
        - MAIL_FROM_ADDRESS: Direccón de correo que se mostrará como remitente
        - MAIL_FROM_NAME: Nombre de usuario que se mostrará como remitente

2. Configurar el sitio web 

    1. Posicionarse dentro de la carpeta `bedelia-web`
    2. Abrir el archivo `src/environments/environment.prod.ts` y configurar las variables:

        - apiURL: URL donde se encuentra publicada la API REST

    3. Compilar para producción

        ```bash
        ng build --prod
        ```

3. Configurar el sitio web 

    1. Posicionarse dentro de la carpeta `bedelia-web`
    2. Abrir el archivo `src/environments/environment.prod.ts` y configurar las variables:

        - apiURL: URL donde se encuentra publicada la API REST

    3. Compilar para producción

        ```bash
        ng build --prod
        ```

## Ejecutar los contenedores

1. En la raíz del repositorio, ejecutar:

    ```bash
    docker-compose up
    ```

2. Si es la primera vez que se ejecuta, se debe generar la estructura de la base de datos, para ello:

    1. Posicionarse en la carpeta `bedelia-rest`
    2. Ejecutar el comando:

        ```bash
        php artisan migrate
        ```
