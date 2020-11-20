<h2>¡Hola {{ $nombre }}!</h2>

<p>Le informamos que su inscripción a la carrera <b>{{ $nombreCarrera }}</b> ha sido confirmada.</p>

<p>Para acceder a la plataforma con su nuevo usuario, debe dirigirse a <a href="{{ $webUrl }}">{{ $webUrl }}</a> e inicie sesión con los siguientes datos:</p>

<ul>
	<li><b>Usuario: </b>{{ $usuario }}</li>
	<li><b>Contraseña: </b>{{ $contrasenia }}</li>
</ul>

<small>No responder este correo</small>

