<?php
const porcentaje = 10;

Class Devolucion
{

/**Guardar en el archivo (devoluciones.json y cupones.json):
a- Se ingresa el número de pedido y la causa de la devolución. El número de pedido debe existir, se ingresa una
foto del cliente enojado,esto debe generar un cupón de descuento(id, devolucion_id, porcentajeDescuento,
estado[usado/no usadol]) con el 10% de descuento para la próxima compra. */

public $motivo;//
public $estado;//
public $devolucion_id;
public $id;
public $porcentajeDescuento;//
public $pedidoNro;//

public function __construct($pedidoNro,$motivo,$id,$estado,$devolucion_id,$porcentajeDescuento)
{
    $rutaIdDescuento = "idDescuento.csv";
    $this->pedidoNro = $pedidoNro;
    $this->motivo = $motivo;
    $this->id = $id;//relacion id interno de la venta
    
    if(isset($estado))
    {
        $this->estado = $estado;
    }
    else
    {
        $this->estado = "no usado";
    }

    if(isset($porcentajeDescuento))
    {
        $this->porcentajeDescuento = $porcentajeDescuento;
    }
    else
    {
        $this->porcentajeDescuento = porcentaje;
    }

    if(isset($devolucion_id))
    {
        $this->devolucion_id = $devolucion_id;
    }
    else
    {
        $this->devolucion_id=controlID($rutaIdDescuento);
    }

}

public static function verificarDevolucion($nro)
{
    $ruta = "cupones.json";
    $control = 0;
    $listadoAux= Devolucion :: cargarCuponesJSON($ruta);

    foreach($listadoAux as $cupon)
    {
        if($cupon->pedidoNro == $nro)
        {
            $control = 1 ;
            break;
        }
    }

    return $control;
}
public static function buscarPedido($listado,$nro)
{
    if(isset($listado) && $nro>0)
    {
        
        $retornarID = 0;
        foreach($listado as $compra)
        {
            if($compra->pedidoNro == $nro)
            {
               $retornarID = $compra->idVenta;
               break;
            }
        }

        if(Devolucion :: verificarDevolucion($nro) == 1)
        {
            $retornarID = -1;
        }

        return $retornarID;
    }
}

public static function guardarCupones($listado,$path)
{
        if(isset($path))
        {
            $archivo = fopen($path,"w");
            fwrite($archivo,json_encode($listado));
            fclose($archivo);
        }
        else
        {
            echo "ocurrio un error con guardar el listado en json\n";
        }
}

public static function cargarCuponesJSON($path)
{
        if(filesize($path))
        {
            $obtenerDatosArchJson = file_get_contents($path);

            $lista = json_decode($obtenerDatosArchJson, true);
            $listaRetorno = array();
            foreach($lista as $d)
            {
                    $aux = new Devolucion($d["pedidoNro"],$d["motivo"],$d["id"],$d["estado"],
                                          $d["devolucion_id"],$d["porcentajeDescuento"]);
                    $listaRetorno[] = $aux;
                
            }

            return $listaRetorno;
        } 
}

public static function guardarDevoluciones($listado,$path)
{
        if(isset($path) && isset($listado))
        {
            $listaDevolucion = array();
            foreach($listado as $d)
            {
                $aux = array("motivo"=>$d->motivo,
                             "pedidoNro"=>$d->pedidoNro,
                             "id"=>$d->devolucion_id );
                array_push($listaDevolucion,$aux);
            }

            $archivo = fopen($path,"w");
            fwrite($archivo,json_encode($listaDevolucion));
            fclose($archivo);
        }
        else
        {
            echo "ocurrio un error con guardar el listado en json\n";
        }
}

public static function cargarDevolucionesJSON($path)
{
        if(filesize($path))
        {
            $obtenerDatosArchJson = file_get_contents($path);

            $lista = json_decode($obtenerDatosArchJson, true);
            $listaRetorno = array();
            foreach($lista as $d)
            {
                 array_push($listaRetorno,$d);
            }

            return $listaRetorno;
        } 
}

public static function moverImagenDevolucion($archivo,$d)
{

        if(isset($archivo))
        {   
            //descuento(id, devolucion_id, porcentajeDescuento,estado[usado/no usadol])
            $destino = "C:\Users\SchryD\Desktop\ImagenesDeDevolucion/2023/".$archivo["name"];
            $nuevoNombre = "$d->devolucion_id"."$d->estado".".png";
            
            if(move_uploaded_file($archivo["tmp_name"], $destino))
            {
                echo "\nSe movio exitosamente la foto\n";
                if(rename($destino, "C:\Users\SchryD\Desktop\ImagenesDeDevolucion/2023/$nuevoNombre"))
                {
                    echo "\nSe cambio el nombre de la foto\n";
                }
            }
            else
            {
                echo "no se movio la imagen\n";
            }
            
        }
        else
        {
            echo "Ocurrio un error con la imagen";
        }
}

public static function bajaCupon($listado,$id,$ruta)
{
    if(isset($listado) && $id>0)
    {

        $control = 0;
        foreach($listado as $cupon)
        {
            if($cupon->id == $id)
            {

                $cupon->estado = "usado";
                $control = 1;
                break;
            }
        }

        if($control == 1)
        {
            Devolucion :: guardarCupones($listado,$ruta);

        }
        
        return $control;
    }
}

public static function listarCuponesYDevoluciones($ruta1,$ruta2)
{
$listadoCupon = Devolucion :: cargarCuponesJSON($ruta1);
$listadoDevolucines = Devolucion :: cargarDevolucionesJSON($ruta2);

foreach($listadoCupon as $cupon)
{
    foreach($listadoDevolucines as $devolucion)
    {
        if($cupon->devolucion_id == $devolucion["id"])
        {
            print_r($cupon);
            break;
        }
    }
}
}

public static function listarSoloCupones($ruta1)
{
    $listadoCupon = Devolucion :: cargarCuponesJSON($ruta1);
    foreach($listadoCupon as $cupon)
    {
        print_r($cupon);
    }
}

public static function listarCuponesDevolucionesUsadas($ruta1,$ruta2)
{
$listadoCupon = Devolucion :: cargarCuponesJSON($ruta1);
$listadoDevolucines = Devolucion:: cargarDevolucionesJSON($ruta2);

foreach($listadoCupon as $cupon)
{
    foreach($listadoDevolucines as $devolucion)
    {
        if($cupon->devolucion_id == $devolucion["id"] && $cupon->estado == "usado")
        {
            print_r($cupon);
            break;
        }
    }
}
}




}
?>