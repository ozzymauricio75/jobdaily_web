<b>Errores de PHP</b>
<pre>
<?php

$mensajes = system("/usr/bin/tail -20 /var/log/apache2/error.log", $codigo);

echo $mensajes;

?>
</pre>
<b>Errores de SQL y Sistema Operativo</b>
<pre>
<?php

//$mensajes = system("/usr/bin/tail -20 /var/log/syslog", $codigo);
$mensajes = system("/usr/bin/tail -20 /var/log/debug", $codigo);

echo $mensajes;

?>
</pre>
