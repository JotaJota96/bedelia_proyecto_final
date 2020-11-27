<h2>¡Hola {{ $nombre }}!</h2>
<p>Le informamos que su usuario de UTEC ha sido creado exitosamente.</p>
<p>Para acceder a la plataforma debe dirigirse a <a href="{{ $webUrl }}">{{ $webUrl }}</a> e iniciar sesión con los siguientes datos:</p>

<ul>
	<li><b>Usuario: </b>{{ $usuario }}</li>
	<li><b>Contraseña: </b>{{ $contrasenia }}</li>
</ul>

<small>No responder este correo</small>

