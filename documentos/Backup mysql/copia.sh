#!/bin/bash

# Datos para la copia de seguridad de la versi�n local
USUARIOBD=proyecto
CLAVEBD=proyecto
NOMBREBD=proyecto
SERVIDORBD=localhost
DIRECTORIOCOPIA=/home/backup
ORIGENCOPIA=/home/linuxcali
FECHACOPIA=`date +"%Y%m%d%H%M"`
ARCHIVOSQL="$DIRECTORIOCOPIA/$NOMBREBD-$FECHACOPIA.sql"
ARCHIVOTAR="$DIRECTORIOCOPIA/$NOMBREBD-$FECHACOPIA.tar"
COMPRESOR="/bin/bzip2"

# Datos para modificar propietarios de archivos
USUARIO=linuxcali
GRUPO=linuxcali

# Comando para copia de seguridad de la base de datos
COPIASQL="/usr/bin/mysqldump -q --default-character-set=latin1 --set-charset"
COPIASQL="$COPIASQL -h $SERVIDORBD -u $USUARIOBD -p$CLAVEBD $NOMBREBD"

# Comando para copia de seguridad de los archivos de la aplicaci�n (codigo php, html y js)
COPIACODIGO="/bin/tar -cf $ARCHIVOTAR"

# Crear copia de seguridad del contenido de la base de datos
$COPIASQL > $ARCHIVOSQL
$COMPRESOR $ARCHIVOSQL

# Crear copia de seguridad de los archivos de la aplicacion
cd $ORIGENCOPIA;
$COPIACODIGO *
$COMPRESOR $ARCHIVOTAR

# Eliminar archivos de mas de 7 dias
find $DIRECTORIOCOPIA -mtime +7 -exec rm -f  '{}' \;
