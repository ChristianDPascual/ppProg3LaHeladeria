<?php

require_once("Venta.php");
require_once("Helados.php");
require_once("Devolucion.php");
include("funciones.php");
$ruta1 = "cupones.json";
$ruta2 = "devoluciones.json";
echo "cupon y devoluciones\n";
Devolucion :: listarCuponesYDevoluciones($ruta1,$ruta2);
echo "\nsolo cupones\n\n";
Devolucion :: listarSoloCupones($ruta1);
echo "\ncupones usados\n\n";
Devolucion :: listarCuponesDevolucionesUsadas($ruta1,$ruta2);

?>