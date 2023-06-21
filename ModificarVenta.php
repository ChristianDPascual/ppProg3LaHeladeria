<?php
require_once("Venta.php");
require_once("Helados.php");
include("funciones.php");

$ruta = "venta.json";
$datos = json_decode(file_get_contents('php://input'));


if(filesize($ruta)>0)
{
      $listado = Cliente :: cargarVentasJSON($ruta);
      
      if(Cliente :: modificarVenta($listado,$datos,$ruta) == 1)
      {
        echo "se modifico con exito\n";
      }
      else
      {
        echo "no se encontraron coincidencias\n";
      }
}
else
{
    echo "no hay ventas realizadas\n";
}


?>