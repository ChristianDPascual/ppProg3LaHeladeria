<?php
/*
Datos a consultar:
a- La cantidad de Helados vendidos en un día en particular(se envía por parámetro), si no se pasa fecha, se
muestran las del día de ayer.
b- El listado de ventas de un usuario ingresado.
c- El listado de ventas entre dos fechas ordenado por nombre.
d- El listado de ventas por sabor ingresado.
e- El listado de ventas por vaso Cucurucho.
*/
require_once("Venta.php");
require_once("Helados.php");
include("funciones.php");


$path = "venta.json";

$fechaIni = $_GET["fechaIni"];
$fechaFin = $_GET["fechaFin"];
$mail = $_GET["mail"];
$sabor = $_GET["sabor"];
$vaso = $_GET["vaso"];
$control = 0;


$listado = Cliente :: cargarVentasJSON($path);

if(isset($listado))
{
    if(Cliente :: filtrarFechas($listado,$fechaIni,$fechaFin)==1)
    {
        $control = 1; 
    }


        if(!empty($mail))
        {
            if(Cliente :: filtrarMail($listado,$mail)==1)
            {
                $control = 1; 
            }
        }

        if(!empty($sabor))
        {
            if(Cliente :: filtrarSabor($listado,$sabor) == 1)
            {
                $control = 1;
            }
      
        }
        
        if(!empty($vaso))
        {
            if(Cliente :: filtrarVaso($listado))
            {
                $control = 1;

            }
        }


    if($control == 0)
    {
        echo "No se encontraron coincidencias\n";
    }
}
else
{
    echo "no se pudo mostrar ninguna consulta, ya que no hay ventas realizadas hasta el momento\n";
}



?>