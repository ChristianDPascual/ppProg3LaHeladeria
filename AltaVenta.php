<?php

require_once("Venta.php");
require_once("Helados.php");
require_once("Devolucion.php");
include("funciones.php");

$path = "C:\Users\SchryD\Desktop\ImagenesDeHelados/2023/";
$ruta = "venta.json";
$ruta2 = "cupones.json";
$nombre = $_POST["nombre"];
$mail = $_POST["mail"];
$tipo = $_POST["tipo"];
$fecha = $_POST["fecha"];
$sabor = $_POST["sabor"];
$cantidad = $_POST['cantidad'];
$vaso= $_POST['vaso'];
$archivo= $_FILES['foto'];

if(validarCadena($nombre) && validarCadena($mail) && validarCondicionTipo($tipo) && validarNumero($cantidad)
  && validarCadena($sabor) && validarFecha($fecha) && validarArchivos($archivo)
  && validarCondicionVaso($vaso))
{
  if(Helado:: consultarStock($sabor,$tipo,$cantidad,$vaso) == 1)
  {
    if(filesize($ruta)>0)
    {
      $listado = Cliente :: cargarVentasJSON($ruta);
    }

    $cliente = new Cliente($nombre,$mail,$fecha,$sabor,$tipo,$cantidad,$vaso,null,null,null);

    if(isset($listado))
    {
      $listadoCupones = Devolucion :: cargarCuponesJSON($ruta2);//cargar listadoJson cupones
      if(filesize($ruta2)>0 && ($id=Cliente::tieneCupon($listado,$listadoCupones,$nombre,$mail))>0)
      {//venta con descuento
        if(Cliente::realizarVenta($cliente) == 1)
        {
          $cliente = Cliente::aplicarDescuento($cliente);
          array_push($listado,$cliente);
          Cliente :: guardarVenta($listado,$ruta);
          Cliente :: moverImagenVenta($archivo,$cliente);
  
          if(Devolucion :: bajaCupon($listadoCupones,$id,$ruta2) == 1)
          {
            print_r($cliente);
            echo "el cupon se utilizo correctamente\n";
          }
        }

      }else
      {
        if(Cliente::realizarVenta($cliente) == 1)
        {
          array_push($listado,$cliente);
          Cliente :: guardarVenta($listado,$ruta);
          Cliente :: moverImagenVenta($archivo,$cliente);
          print_r($cliente);
        }
      }
    }
    else
    {
      if(Cliente::realizarVenta($cliente) == 1)
      {
        $nuevaLista = array();
        $nuevaLista[] = $cliente;
        Cliente :: guardarVenta($nuevaLista,$ruta);
        Cliente :: moverImagenVenta($archivo,$cliente);
      }
    }
  }
  else
  {
      echo "No hay producto disponible para realizar la venta\n";
  }

}
else
{
  echo "ocurrio un error al generar la venta\n";
}


?>