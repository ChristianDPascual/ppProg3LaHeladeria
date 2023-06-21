<?php

require_once("Helados.php");
Class Cliente extends Helado
{
    public $nombre;
    public $mail;
    public $fecha;
    public $pedidoNro;
    public $idVenta;
    public $estado;
    public $cantidad;
    public $total;
    

    public function __construct($nombre,$mail,$fecha,$sabor,$tipo,$cantidad,$vaso,$pedidoNro,$idVenta,$estado)
    {
        $ruta1 = "nroPedido.csv";
        $ruta2 = "idVentas.csv";
        $this->nombre = $nombre;
        $this->mail = $mail;
        $this->fecha = $fecha;
        $this->sabor=$sabor;
        $this->tipo=$tipo;
        $this->vaso=$vaso;
        $this->cantidad=$cantidad;
        $this->total= Cliente::calcularTotal($sabor,$tipo,$vaso,$cantidad);

        if(isset($estado))
        {
            $this->estado = $estado;
        }
        else
        {
            $this->estado = 1;
        }

        if(isset($pedidoNro))
        {
            $this->pedidoNro = $pedidoNro;
        }
        else
        {
            $this->pedidoNro = controlIDVenta($ruta1);
        }

        if(isset($idVenta))
        {
            $this->idVenta = $idVenta;
        }
        else
        {
            $this->idVenta = controlID($ruta2);
        }
        
    }

    public static function calcularTotal($sabor,$tipo,$vaso,$cantidad)
    {
        if(isset($sabor) && isset($vaso) && isset($tipo) && $cantidad>0)
        {
            $rutaHelado = "heladeria.json";
            $listado = Helado :: cargarJson($rutaHelado);
            $precio = 0;

            foreach($listado as $h)
            {
                if($h->sabor == $sabor && $h->tipo == $tipo && $h->vaso == $vaso)
                {
                    $precio = $h->precio;
                    break;
                }
            }
            $total = $precio * $cantidad;

            return $total;

        }
        else
        {
            echo "ocurrio un error al calcular el costo total de la venta\n";
        }
    }

    public static function realizarVenta($c)
    {
        $path = "heladeria.json";
    
        if(isset($c) && file_exists($path))
        {
            $listado = Helado :: cargarJson($path);

            if(count($listado)>0)
            {
                $contador = 0;
                foreach($listado as $helado)
                {
                    if($helado->sabor == $c->sabor && $helado->tipo == $c->tipo && $helado->stock >= $c->cantidad && $helado->vaso == $c->vaso)
                    {
                        $helado->stock = $helado->stock - $c->cantidad;
                        $cant =$helado->stock;
                        $contador ++;
                        break;
                    }
                }
    
                if($contador == 0)
                {
                    echo "no se pudo realizar la venta\n";   
                    return 0;
                }
                else
                {
                    Helado :: guardarJson($listado,$path);
                    echo "se realizo la venta con exito";
                    return 1;
                }
            }
            else
            {
                echo "no hay stock de helados\n";
                return 0;
            }
        
        }
        else
        {
            echo "ocurrio un error al tratar de realizar venta\n"; 
            return 0;
        }


    }

    public static function aplicarDescuento($cliente)
    {
        if(isset($cliente))
        {
            $descuento = $cliente->total * 0.1;
            $cliente->total = $cliente->total - $descuento;
            return $cliente;
        }
        
    }

    public static function guardarVenta($listado,$path)
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

    public static function moverImagenVenta($archivo,$v)
    {

        if(isset($archivo))
        {   
            //sabor+tipo+vaso+mail(solo usuario hasta el @) y fecha de la venta en la carpeta
            $email = explode("@",$v->mail);
            $destino = "C:\Users\SchryD\Desktop\ImagenesDeLaVenta/2023/".$archivo["name"];
            $nuevoNombre = "$v->sabor"."$v->tipo"."$v->vaso".$email[0]."$v->fecha".".png";
            
            if(move_uploaded_file($archivo["tmp_name"], $destino))
            {
                echo "\nSe movio exitosamente la foto\n";
                if(rename($destino, "C:\Users\SchryD\Desktop\ImagenesDeLaVenta/2023/$nuevoNombre"))
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

    public static function cargarVentasJSON($path)
    {
        if(filesize($path))
        {
            $obtenerDatosArchJson = file_get_contents($path);

            $lista = json_decode($obtenerDatosArchJson, true);
            $listaRetorno = array();
            foreach($lista as $c)
            {
                
                //$mail,$fecha,$sabor,$tipo,$stock,$vaso,$pedidoNro,$idVenta,$estado);

                    $aux = new Cliente($c["nombre"],$c["mail"],$c["fecha"],$c["sabor"],$c["tipo"],$c["cantidad"],
                    $c["vaso"],$c["pedidoNro"],$c["idVenta"],$c["estado"]);
                    $listaRetorno[] = $aux;
                
            }

            return $listaRetorno;
        } 
    }

    public static function filtrarMail($lista,$mail)
    {
        if(isset($lista))
        {
            $contador = 0;

            foreach($lista as $c)
            {
                if($c->mail == $mail)
                {
                    print_r($c);
                    $contador++;
                }
            }

            if($contador>0)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public static function filtrarSabor($lista,$sabor)
    {
        if(isset($lista))
        {
            $contador = 0;
            foreach($lista as $c)
            {
                if($c->sabor == $sabor)
                {
                    print_r($c);
                    $contador++;
                }
            }

            if($contador>0)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    
    }

    public static function filtrarVaso($lista)
    {
        if(isset($lista))
        {
            $contador = 0;
            foreach($lista as $c)
            {
                if($c->vaso == "cucurucho")
                {
                    print_r($c);
                    $contador++;
                }
            }

            if($contador>0)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    
    }

    public static function filtrarFechas($lista,$fechaIni,$fechaFin)
    {
        if(isset($lista))
        {
            $control = 0;
            if(!empty($fechaIni) && !empty($fechaFin))
            {
                $fechaI = date('DD-MM-YYYY',strtotime($fechaIni));
                $fechaF = date('DD-MM-YYYY',strtotime($fechaFin));

                foreach($lista as $c)
                {
                    $fechaAux = date('DD-MM-YYYY',strtotime($c->fecha));
                    
                    if($fechaAux >= $fechaI && $fechaAux <= $fechaF )
                    {
                        print_r($c);
                         $control++;
                    }
                }

            }
            else
            {
                if(!empty($fechaIni) && $control == 0)
                {
                    $fechaI = date('DD-MM-YYYY',strtotime($fechaIni));
                    foreach($lista as $c)
                    {
                        $fechaAux = date('DD-MM-YYYY',strtotime($c->fecha));
                        if($fechaAux == $fechaI)
                        {
                           print_r($c);
                          
                           $control++;
                        }
                    }
                }
                else
                {
                    if($control == 0)
                    {
                        $fechaHoy = date('d-m-Y');
                        $fechaAyer = date('d-m-Y',strtotime($fechaHoy.'-1 day'));

                        foreach($lista as $c)
                        {
                            $fechaAux = date('d-m-Y',strtotime($c->fecha));
                            if($fechaAux == $fechaAyer)
                            {
                               print_r($c);
                               $control++;
                            }
                        }
                    }
                }
            }

            if($control == 1)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public static function modificarVenta($listado,$obj,$ruta)
    {
        if(isset($listado) && isset($obj))
        {
            $control = 0;

            foreach($listado as $c)
            {
                if($c->pedidoNro == $obj->pedidoNro)
                {

                    $c->nombre = $obj->nombre;
                    $c->mail = $obj->mail;
                    $c->tipo = $obj->tipo;
                    $c->vaso = $obj->vaso;
                    $c->cantidad = $obj->cantidad;
                    $c->sabor = $obj->sabor;
                    $c->total = Cliente::calcularTotal($obj->sabor,$obj->tipo,$obj->vaso,$obj->cantidad);
                    $control = 1;
                    break;
                    
                }
                
            }

            if($control == 1)
            {
                Cliente :: guardarVenta($listado,$ruta);
                return 1;

            }
            else
            {
                return 0;
            }
        }
        else
        {
            echo "Ocurrio un error al recibir un objeto\n";
            return 0;
        }

    }

    public static function borrarVenta($listado,$obj,$ruta)
    {
        if(isset($listado) && isset($obj))
        {
            $control = 0;

            foreach($listado as $c)
            {
                if($c->pedidoNro == $obj->pedidoNro)
                {

                    if($c->estado == 0)
                    {
                        $control = -1;
                        break;
                    }
                    else
                    {
                        $c->estado = 0;
                        $control = 1;
                        Cliente :: moverImagenVentaBorrada($c);
                        break;
                    }
                    
                }
                
            }

            if($control == 1)
            {
                Cliente :: guardarVenta($listado,$ruta);
                return $control;

            }
            else
            {
                return $control;
            }
        }
        else
        {
            echo "Ocurrio un error al recibir un objeto\n";
            return $control;
        }   
    }

    public static function moverImagenVentaBorrada($v)
    {

        if(isset($v))
        {   
            //sabor+tipo+vaso+mail(solo usuario hasta el @) y fecha de la venta en la carpeta
            $email = explode("@",$v->mail);
            $nuevoNombre = "$v->sabor"."$v->tipo"."$v->vaso".$email[0]."$v->fecha".".png";
            
            $destino = "C:\Users\SchryD\Desktop\ImagenesDeLaVenta/2023/$nuevoNombre";
            $destinoBackup = "C:\Users\SchryD\Desktop\ImagenesBackupVentas/2023/$nuevoNombre";
            
            if(rename($destino, $destinoBackup))
            {
                    echo "\nSe movio exitosamente la foto\n";
            }
            else
            {
                echo "no se movio la imagen\n";
            }
            
        }
        else
        {
            echo "Ocurrio un error al mover la imagen";
        }
    }


    public static function tieneCupon($listadoVentas,$listadoCupon,$nombre,$mail)
    {
        if(isset($listadoCupon) && isset($listadoVentas) && isset($nombre) && isset($nombre))
        {
            $retorno = 0;
            foreach($listadoVentas as $v)
            {
                if($v->nombre == $nombre && $v->mail==$mail)
                {
                    foreach($listadoCupon as $cupon)
                    {
                        if($cupon->id == $v->idVenta && $cupon->estado == "no usado")
                        {
                            $retorno = $v->idVenta;
                            break;
                        }
                    }
                }
            }

            return $retorno;
        }
    }

}


?>