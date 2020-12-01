# Instrucciones

## Índice

- [Instrucciones](#instrucciones)
  - [Índice](#índice)
  - [Requisitos](#requisitos)
  - [Clonar el repositorio](#clonar-el-repositorio)
  - [Configurar para producción](#configurar-para-producción)
    - [Configurar la API REST](#configurar-la-api-rest)
    - [Configurar el sitio web](#configurar-el-sitio-web)
    - [Configurar la aplicación PWA](#configurar-la-aplicación-pwa)
  - [Instalar dependencias y compilar](#instalar-dependencias-y-compilar)
    - [Instalar dependencias y compilar (con Docker)](#instalar-dependencias-y-compilar-con-docker)
    - [Instalar dependencias y compilar (sin Docker)](#instalar-dependencias-y-compilar-sin-docker)
  - [Ejecutar contenedores para producción](#ejecutar-contenedores-para-producción)
  - [Creación de tablas en la base de datos](#creación-de-tablas-en-la-base-de-datos)
  - [Inserción de los datos de prueba](#inserción-de-los-datos-de-prueba)

## Requisitos

- **git**: 2.25.1
- **Docker**: 19.03.12

## Clonar el repositorio

Clonar el [repositorio](https://github.com/JotaJota96/bedelia_proyecto_final) y luego posicionarse dentro de el.

```bash
git clone https://github.com/JotaJota96/bedelia_proyecto_final
cd bedelia_proyecto_final
```

Recuerde que para cada sección de esta guía, debe estar posicionado en la raíz del repositorio.

## Configurar para producción

### Configurar la API REST

1. Posicionarse dentro de la carpeta `bedelia-rest`

    ```bash
    cd bedelia-rest
    ```

2. Crear el archivo `.env` y colocarle el contenido del archivo `.env.example.prod`

    ```bash
    cp .env.example.prod .env
    ```

3. Abrir el archivo `.env` y configurar las siguientes variables:

    - **APP_KEY**: Clave para encriptación
    - **APP_URL**: URL donde se encuentra publicada la API

    - **WEB_URL**: URL del sitio web
    - **WEB_URL_VERIFICAR**: URL de la pagina para verificar la escolaridad

    - **DB_USERNAME**: Nombre de usuario de la base de datos
    - **DB_PASSWORD**: Contraseña del usuario de la base de datos

    - **MAIL_SEND**: Habilita (true) o deshabilita (false) el envío de mails
    - **MAIL_USERNAME**: Dirección de correo desde la que se enviarán los mails
    - **MAIL_PASSWORD**: Contraseña del correo desde la que se enviarán los mails
    - **MAIL_FROM_ADDRESS**: Direccón de correo que se mostrará como remitente
    - **MAIL_FROM_NAME**: Nombre de usuario que se mostrará como remitente

### Configurar el sitio web

1. Posicionarse dentro de la carpeta `bedelia-web`

    ```bash
    cd bedelia-web
    ```

2. Abrir el archivo `src/environments/environment.prod.ts` y configurar las siguientes variables:

   - **apiURL**: URL donde se encuentra publicada la API REST

### Configurar la aplicación PWA

1. Posicionarse dentro de la carpeta `bedelia-pwa`

    ```bash
    cd bedelia-pwa
    ```

2. Abrir el archivo `src/environments/environment.prod.ts` y configurar las siguientes variables:

   - **apiURL**: URL donde se encuentra publicada la API REST

## Instalar dependencias y compilar

### Instalar dependencias y compilar (con Docker)

1. Instalar dependencias de la API REST

    ```bash
    docker run --rm -it \
        -v $PWD/bedelia-rest/:/app \
        composer:1.10.10 \
        bash -c "\
            cd /app; \
            composer install; \
            "
    ```

    Explicación:
      - `docker run` ejecutará un contenedor obedeciendo a los siguientes parámetros
      - `--rm` elimina el contenedor cuando finaliza la ejecución
      - `it` muestra de manera mas clara el proceso
      - `-v $PWD:/usr/src/app` monta la carpeta actual como volumen en `/app` dentro del contenedor
      - `node:12.18.3` es la imágen a utilizar
      - `bash -c "..."` hace que el `bash` del contenedor ejecute los comandos que se le pasan entre las comillas (`"`)

2. Instalar dependencias y compilar el sitio web

    ```bash
    docker run --rm -it \
        -v $PWD/bedelia-web/:/usr/src/app \
        node:12.18.3 \
        bash -c "\
            export NG_CLI_ANALYTICS=ci; \
            cd /usr/src/app; \
            npm install; \
            npm run prod; \
            "
    ```

3. Instalar dependencias y compilar la aplicación PWA

    ```bash
    docker run --rm -it \
        -v $PWD/bedelia-pwa/:/usr/src/app \
        node:12.18.3 \
        bash -c "\
            export NG_CLI_ANALYTICS=ci; \
            cd /usr/src/app; \
            npm install; \
            npm run prod; \
            "
    ```

### Instalar dependencias y compilar (sin Docker)

La instalación de dependencias y compilación sin Docker requiere tener instalado lo siguiente:

- **Node**: 12.18.3
- **npm**: 6.14.6
- **Angular CLI**: 10.1.0
- **php**: 7.4.3
- **composer**: 1.10.10

1. Posicionarse dentro de la carpeta `bedelia-rest` e instalar las dependencias de la API REST

    ```bash
    cd bedelia-rest
    composer install
    ```

2. Posicionarse dentro de la carpeta `bedelia-web`, instalar las dependencias y compilar el sitio web

    ```bash
    cd bedelia-web
    npm install
    ng build --prod
    ```

3. Posicionarse dentro de la carpeta `bedelia-pwa`, instalar las dependencias y compilar la aplicación PWA

    ```bash
    cd bedelia-pwa
    npm install
    ng build --prod
    ```

## Ejecutar contenedores para producción

En la raíz del repositorio, ejecutar el siguiente comando:

```bash
docker-compose up
```

Este comando creará una red dentro de Docker y configurará las direcciones IP de cada contenedor

Los contenedores que quedarán en ejecución serán:

- `bedelia-db`: contiene un servidor de bases de datos MySQL
- `bedelia-phpmyadmin`: contiene un servidor apache que expone la interfaz de **phpMyAdmin** en el puerto `8081`
- `bedelia-rest`: contiene un servidor apache que expone la API REST en el puerto `8000`
- `bedelia-web`: contiene un servidor apache que expone el sitio web en el puerto `80`
- `bedelia-pwa`: contiene un servidor apache que expone la aplicación PWA en el puerto `8080`

Para acceder al sitio web ingrese a <localhost:80>

Para acceder a la aplicación PWA ingrese a <localhost:8080>

## Creación de tablas en la base de datos

Si es la primera vez que se ejecuta, se debe generar la estructura de la base de datos. 
Para ello es necesario tener corriendo el contenedor `bedelia-db` (servidor de base de datos MySQL) y ejecutar el siguiente comando:

```bash
docker exec -it bedelia-rest bash -c "cd /var/www; php artisan migrate"
```

## Inserción de los datos de prueba

1. Acceder a la interfaz de **phpMyAdmin** en <localhost:8081>

2. Ingresar con los siguientes datos:

    - **Usuario**: root
    - **Contraseña**: 1234

3. Abrir la sección para ejecución de consultas SQL de la base de datos `bedelia`

4. Copiar, pegar y ejecutar el script `documentacion/base_de_datos_dataset.sql`
