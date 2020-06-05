<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border=1>\n";
echo "<tr>\n";

echo "<th>Nombre</th>\n";
echo "<th>Email</th>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td><font color=green>Manuel Gomez</font></td>\n";
echo "<td>manuel@gomez.com</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td><font color=blue>Pago gomez</font></td>\n";
echo "<td>paco@gomez.com</td>\n";
echo "</tr>\n";

echo "</table>\n";
?>