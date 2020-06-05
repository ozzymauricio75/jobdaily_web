<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

class Cadena {

	/*** Validar una cadena de texto para que tenga el formato correcto de dirección IP ***/
	public static function validarDireccionIP($direccion) {

		if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$direccion)) {
			$segmentos = explode(".",$direccion);

			foreach($segmentos as $segmento) {
				if(intval($segmento) > 255 || intval($segmento) < 0) {
					return false;
				}
			}

            return true;

		} else {
			return false;
		}
	}

    /*** Validar que una cadena de texto solo tenga letras mayúsculas a excepción de algunos caracteres permitidos ***/
    public static function validarContrasena($cadena, $debil = false, $insensible = false) {

        if (preg_match("/^([a-zA-Z0-9\.\-])@([a-zA-Z0-9\.\-]+)\.([a-z]{2,4})$/", $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    /*** Validar que una cadena de texto solo tenga letras mayúsculas a excepción de algunos caracteres permitidos ***/
    public static function validarCorreo($cadena) {

        //if (preg_match("/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])([-a-z0-9_])+([a-z0-9])*(\.([a-z0-9])([-a-z0-9_-])([a-z0-9])+)*$/i", $cadena)) {
        if (preg_match("/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/", $cadena)) {            
            return true;
        } else {
            return false;
        }
    }

    /*** Validar que una cadena de texto solo tenga letras mayúsculas a excepción de algunos caracteres permitidos ***/
    public static function validarMayusculas($cadena, $longitudMinima = 0, $longitudMaxima = 0, $permitidos = "") {

        if (!$longitudMaxima) {
            $longitudMaxima = strlen($cadena);
        }

        $longitud = "{".$longitudMinima.",".$longitudMaxima."}";

        if (preg_match("/^([A-Z".$permitidos."]".$longitud.")$/", $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    /*** Validar que una cadena de texto solo tenga letras minúsculas a excepción de algunos caracteres permitidos ***/
    public static function validarMinusculas($cadena, $longitudMinima = 0, $longitudMaxima = 0, $permitidos = "") {

        if (!$longitudMaxima) {
            $longitudMaxima = strlen($cadena);
        }

        $longitud = "{".$longitudMinima.",".$longitudMaxima."}";

        if (preg_match("/^([a-z".$permitidos."]".$longitud.")$/", $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    /*** Validar que una cadena de texto solo tenga numeros a excepción de algunos caracteres permitidos ***/
    public static function validarNumeros($cadena, $longitudMinima = 0, $longitudMaxima = 0, $permitidos = "") {

        if (!$longitudMaxima) {
            $longitudMaxima = strlen($cadena);
        }

        $longitud = "{".$longitudMinima.",".$longitudMaxima."}";

        if (preg_match("/^([0-9".$permitidos."]".$longitud.")$/", $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    /*** Generar una expresión regular a partir de una cadena de texto dada para realizar búsquedas ***/
    public static function expresionRegular($cadena, $completa = true) {
        $cadena = utf8_decode($cadena);

        if (strlen($cadena) <= 2) {
            return "[[:<:]]".$cadena."[[:>:]]";
        }

        /*** Eliminar espacios en blanco duplicados ***/
        while (strpos($cadena, "  ")) {
            $cadena = str_replace("  ", " ", $cadena);
        }

        $palabras = array();

        /*** Verificar si se debe buscar la cadena o una subcadena ***/
        if ($completa) {
            /*** Verificar si se debe buscar cualquier palabra o la frase exacta ***/
            if (preg_match("/([^\"])*([\"$])/", $cadena)) {
                $cadena      = str_replace("\\\"", "", $cadena);
                $palabras[0] = $cadena;
            } else {
                $palabras = explode(" ", $cadena);
            }
        } else {
            $palabras[0] = $cadena;
        }

        $palabrasExpReg = array();

        foreach ($palabras as $palabra) {
            $palabra = strtolower($palabra);
            $palabra = preg_replace("/ce|cé|cÉ|se|sé|sÉ|ze|zé|zÉ/", "[cszx]e", $palabra);
            $palabra = preg_replace("/ci|cí|cÍ|si|sí|sÍ|zi|zí|zÍ/", "[cszx]i", $palabra);
            $palabra = preg_replace("/sa|sá|sÁ|za|zá|zÁ/", "[szx]a", $palabra);
            $palabra = preg_replace("/so|só|sÓ|zo|zó|zÓ/", "[szx]o", $palabra);
            $palabra = preg_replace("/su|sú|sÚ|zu|zú|zÚ/", "[szx]u", $palabra);
            $palabra = preg_replace("/ca|cá|cÁ|ka|ká|kÁ/", "[ck]a", $palabra);
            $palabra = preg_replace("/co|có|cÓ|ko|kó|kÓ/", "[ck]o", $palabra);
            $palabra = preg_replace("/cu|cú|cÚ|ku|kú|kÚ/", "[ck]u", $palabra);
            $palabra = preg_replace("/je|jé|jÉ|ge|gé|gÉ/", "[jg]e", $palabra);
            $palabra = preg_replace("/ji|jí|jÍ|gi|gí|gÍ/", "[jg]i", $palabra);
            $palabra = preg_replace("/que|qué|quÉ|ke|ké|kÉ/", "(qu|k)e", $palabra);
            $palabra = preg_replace("/qui|quí|quÍ|ki|kí|kÍ/", "(qu|k)i", $palabra);
            $palabra = preg_replace("/cl|kl|cr|kr/", "(cl|kl|cr|kr)", $palabra);
            $palabra = preg_replace("/h/", "h?", $palabra);
            $palabra = preg_replace("/[vb]/", "[vb]", $palabra);
            $palabra = preg_replace("/y|ll/", "(y|ll)", $palabra);
            $palabra = preg_replace("/s |z /", "[szx] ", $palabra);
            $palabra = preg_replace("/s$|z$/", "[szx]?", $palabra);
            $palabra = preg_replace("/[a]$/", "a[s]?", $palabra);
            $palabra = preg_replace("/[e]$/", "e[s]?", $palabra);
            $palabra = preg_replace("/[i]$/", "i[s]?", $palabra);
            $palabra = preg_replace("/[o]$/", "o[s]?", $palabra);
            $palabra = preg_replace("/[u]$/", "u[s]?", $palabra);
            $palabra = preg_replace("/[aáÁ]/", "h?[aáÁ]", $palabra);
            $palabra = preg_replace("/[eéÉ]/", "h?[eéÉ]", $palabra);
            $palabra = preg_replace("/[yiíÍ]/", "h?[iíÍy]", $palabra);
            $palabra = preg_replace("/[oóÓ]/", "h?[oóÓ]", $palabra);
            $palabra = preg_replace("/[uúÚ]/", "h?[uúÚ]", $palabra);
            $palabra = preg_replace("/[nñÑ]/", "[nñÑ]", $palabra);
            $palabra = preg_replace("/\./", "[\.]?", $palabra);

            if ($completa) {
                $palabra = "[[:<:]]".$palabra."[[:>:]]";
            }

            $palabrasExpReg[] = $palabra;
        }

        $cadena = implode("|", $palabrasExpReg);
        return $cadena;
    }

    public static function generarCadenaAleatoria($largo = 32) {

        $cadena = substr(md5(uniqid(rand(), true)), 0, $largo);
        return $cadena;
    }

    /*** Determinar si una cadena contiene o no caracteres en UTF-8 ***/
    public static function contieneUTF8($cadena) {

        // Basada en http://w3.org/International/questions/qa-forms-utf-8.html
        return preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )*$%xs', $cadena);
    }

    public static function numeroaletras($num, $fem = true, $dec = true) {
        $matuni[2]  = "dos";
        $matuni[3]  = "tres";
        $matuni[4]  = "cuatro";
        $matuni[5]  = "cinco";
        $matuni[6]  = "seis";
        $matuni[7]  = "siete";
        $matuni[8]  = "ocho";
        $matuni[9]  = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3]  = 'mill';
        $matsub[5]  = 'bill';
        $matsub[7]  = 'mill';
        $matsub[9]  = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4]  = 'millones';
        $matmil[6]  = 'billones';
        $matmil[7]  = 'de billones';
        $matmil[8]  = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

        $num = trim((string)@$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        }else
            $neg = '';
        while ($num[0] == '0') $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (! (strpos(".,'''", $n) === false)) {
                if ($punt) break;
                else{
                    $punt = true;
                    continue;
                }

            }elseif (! (strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0') $zeros = false;
                    $fra .= $n;
                }else
                    $ent .= $n;
            }else
                break;
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and ! $zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' cero';
                elseif ($s == '1')
                    $fin .= $fem ? ' un' : ' un';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        }else
            $fin = '';
        if ((int)$ent === 0) return 'Cero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while ( ($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'un';
                $subcent = 'os';
            }else{
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
            }elseif ($n2 < 21)
                $t = ' ' . $matuni[(int)$n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) $t = 'i' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else{
                $n3 = $num[2];
                if ($n3 != 0) $t = ' y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' cien' . $t;
            }elseif ($n == 5){
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            }elseif ($n != 0){
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {
            }elseif (! isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                }elseif ($num > 1){
                    $t .= ' mil';
                }
            }elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . 'on';
            }elseif ($num > 1){
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000') $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        return ucfirst($tex);
    }


    public static function reemplazar($plantilla, $reemplazos) {

        preg_match_all("/([A-Z\_1-9]{2,20}\=[0-9]{1,4})[^0-9]/", $plantilla, $claves);

        foreach ($claves[1] AS $indice => $clave) {
            //$plantilla .= $clave.":";
            $datos  = explode("=", $clave);
            $indice = trim($datos[0], "1234567890");

            if ( isset($reemplazos[$indice]) ) {

                $cadena = $reemplazos[$indice];
                $largo  = $datos[1];

                if (!is_array($cadena)) {

                    if (strlen($cadena) < $largo) {
                        $cadena = str_pad($cadena, $largo, "*");
                    } elseif (strlen($cadena) > $largo) {
                        $cadena = substr($cadena, 0, $largo);
                    }

                    $plantilla = str_replace($clave, $cadena, $plantilla);

                } else {

                    foreach ($cadena AS $i => $codigo) {

                        if (strlen($codigo) < $largo) {
                            $codigo = str_pad($codigo, $largo, " ");
                        } elseif (strlen($codigo) > $largo) {
                            $codigo = substr($codigo, 0, $largo);
                        }

                        $clave = $indice.$i."=".$largo;

                        $plantilla =  str_replace($clave, $codigo, $plantilla);

                    }
                }
            }

            $plantilla = str_replace($clave, " ", $plantilla);
            //$plantilla .= $clave." - ";

        }

        return $plantilla;
    }

    /*** Retorna el costo en inventario de un articulo en una fecha determinada - Definir ubicacion: en clase Cadena o en otra ***/
    public static function costoArticulo($articulo, $fecha) {

        $cantidad           = 0;
        $valor_existencias  = 0;

        $consulta_movimientos = SQL::seleccionar(
			array("movimientos_articulos"),
            array("cantidad", "valor", "sentido"),
			"id_articulo = '$articulo' AND fecha < '$fecha'", "", "fecha ASC"
		);

        if (SQL::filasDevueltas($consulta_movimientos)) {
            while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimientos)) {

                if ($datos_movimiento->sentido == "1") {

                    $cantidad           += $datos_movimiento->cantidad;
                    $valor_existencias  += $datos_movimiento->valor;

                } else {

                    $valor_existencias  -= $datos_movimiento->valor;
                    $cantidad           -= $datos_movimiento->cantidad;
                }
            }
        }
        $costo = $valor_existencias / $cantidad;
        return (int)$costo;
    }

}
?>
