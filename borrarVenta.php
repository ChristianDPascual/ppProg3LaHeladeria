<?php
require_once("Venta.php");
require_once("Helados.php");
include("funciones.php");

$ruta = "venta.json";
$datos = json_decode(file_get_contents('php://input'));

if(filesize($ruta)>0)
{
      $listado = Cliente :: cargarVentasJSON($ruta);
      $verificar = Cliente :: borrarVenta($listado,$datos,$ruta);
      if($verificar == 1)
      {
        echo "se borro con exito\n";
      }
      else
      {
        if($verificar == -1)
        {
            echo "Ya se encuentra eliminada\n";
        }
        else
        {
            echo "no se encontraron coincidencias\n";
        }
        
      }
}
else
{
    echo "no hay ventas realizadas\n";
}


?>