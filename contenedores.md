# Contenedores de Docker

## mySQL

Para crear el contenedor por primera vez

```bash
docker run --name cont-mysql -v "$PWD"/database:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=1234 -d mysql:8.0
```

Para correr un contenedor ya creado

```bash
docker restart cont-mysql
```

- **Usuario:** root
- **Contraseña:** 1234

## phpMyAdmin

Para crear el contenedor por primera vez

```bash
docker run --name cont-myadmin -d --link cont-mysql:db -p 8080:80 phpmyadmin
```

Para correr un contenedor ya creado

```bash
docker restart cont-myadmin
```

- **Acceder desde:** <localhost:8080>
