<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los artículos en el sistema">
    function InsertarArticulo($MARCA_ID,$NOMBRE,$PRECIO,$SKU,$DESCRIPCION,$IMAGEN,$USUARIO_CREADOR,$FECHA_HORA_CREACION,$CATEGORIA_ID){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERCIÓN DE ARTICULOS">                 
                      $query  ="INSERT INTO ARTICULOS(MARCA_ID,NOMBRE,PRECIO,SKU,DESCRIPCION,IMAGEN,USUARIO_CREADOR ";
                      $query .= " ,FECHA_HORA_CREACION,CATEGORIA_ID) ";
                      $query .= " VALUES ($MARCA_ID,'$NOMBRE','$PRECIO','$SKU','$DESCRIPCION','$IMAGEN','$USUARIO_CREADOR' ";
                      $query .= " ,'$FECHA_HORA_CREACION',$CATEGORIA_ID);";                 
                    // </editor-fold>
                    if (mysqli_query($conn, $query)){
                        $result = true;
                        mysqli_commit($conn);
                    }
                    else{
                        mysqli_rollback($conn);
                        $result = false;
                    }
            mysqli_close($conn);
        }
        else {
            // FALLO LA CONEXION
            $result = false;
        }
	return $result;
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Actualiza la categoría en el sistema">
    function ActualizaArticulo($ARTICULO_ID,$MARCA_ID,$NOMBRE,$PRECIO,$SKU,$DESCRIPCION,$IMAGEN,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION,$CATEGORIA_ID){
       $conn = ABRIR_CONEXION_MYSQL(FALSE);
       $result = false;
       if ($conn){
           mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                   // <editor-fold defaultstate="collapsed" desc="UPDATE DE LA CATEGORÍA">                 
                     $query  = "UPDATE ARTICULOS ";
                     $query .= "SET ";
                     $query .= "MARCA_ID = $MARCA_ID, ";
                     $query .= "NOMBRE = '$NOMBRE', ";
                     $query .= "PRECIO = '$PRECIO', ";
                     $query .= "SKU = '$SKU', ";
                     $query .= "DESCRIPCION = '$DESCRIPCION', ";
                     $query .= "IMAGEN = '$IMAGEN', ";
                     $query .= "USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                     $query .= "FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION', ";
                     $query .= "CATEGORIA_ID = $CATEGORIA_ID ";
                     $query .= "WHERE ARTICULO_ID = $ARTICULO_ID;";
                   // </editor-fold>
                   if (mysqli_query($conn, $query)){
                       $result = true;
                       mysqli_commit($conn);
                   }
                   else{
                       mysqli_rollback($conn);
                       $result = false;
                   }
           mysqli_close($conn);
       }
       else {
           // FALLO LA CONEXION
           $result = false;
       }
   return $result;
   }
   // </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Muestra los datos de los articulos del sistema">
function MostrarArticulos($ARTICULO_ID,$MARCA_ID,$CATEGORIA_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    $where="";
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LOS ARTÍCULOS EN EL SISTEMA"> 
                if($ARTICULO_ID>0)
        {
            $where=" WHERE ARTICULO_ID=$ARTICULO_ID";
        }
        elseif($SKU===0 || strlen($SKU)>0){
            $where=" WHERE SKU=$SKU;";
        }
        elseif($MARCA_ID>0 && $CATEGORIA_ID>0){
            $where=" WHERE MARCA_ID=$MARCA_ID AND CATEGORIA_ID=$CATEGORIA_ID;";
        }
        elseif($MARCA_ID>0 && $CATEGORIA_ID===0){
            $where=" WHERE MARCA_ID=$MARCA_ID;";
        }
        elseif($MARCA_ID===0 && $CATEGORIA_ID>0){
            $where=" WHERE CATEGORIA_ID=$CATEGORIA_ID;";
        }
        else{
            $where=";";
        }       
                  $select  = "SELECT ARTICULO_ID,MARCA_ID,NOMBRE,PRECIO,SKU,DESCRIPCION,IMAGEN,USUARIO_CREADOR,FECHA_HORA_CREACION ";
                  $select .= " ,USUARIO_MODIFICACION,FECHA_HORA_MODIFICACION,CATEGORIA_ID ";
                  $select .= " FROM ARTICULOS"+$where;
                // </editor-fold>
                echo $select;
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $roles["ARTICULO_ID"]=$row["ARTICULO_ID"];
                            $roles["MARCA_ID"]=$row["MARCA_ID"];
                            $roles["NOMBRE"]=$row["NOMBRE"];
                            $roles["PRECIO"]=$row["PRECIO"];
                            $roles["SKU"]=$row["SKU"];
                            $roles["DESCRIPCION"]=$row["DESCRIPCION"];
                            $roles["IMAGEN"]=$row["IMAGEN"];
                            $roles["USUARIO_CREADOR"]=$row["USUARIO_CREADOR"];
                            $roles["FECHA_HORA_CREACION"]=$row["FECHA_HORA_CREACION"];
                            $roles["USUARIO_MODIFICACION"]=$row["USUARIO_MODIFICACION"];
                            $roles["FECHA_HORA_MODIFICACION"]=$row["FECHA_HORA_MODIFICACION"];
                            $roles["CATEGORIA_ID"]=$row["CATEGORIA_ID"];
                            $result[]=$roles;                            
                        } 
                        
                    mysqli_close($conn);
                        return $result;
                    }
                    else{
                        
                    mysqli_close($conn);
                        return null; 
                    }
                    mysqli_close($conn);
                }
                else {
                    // FALLO LA CONEXION
                    return null; 
                }
}
// </editor-fold>

    $server->register(
        'InsertarArticulo',
        array(
            'MARCA_ID'=>'xsd:int',
            'NOMBRE'=>'xsd:string',
            'PRECIO'=>'xsd:string',
            'DESCRIPCION'=>'xsd:string',
            'IMAGEN'=>'xsd:string',
            'USUARIO_CREADOR'=>'xsd:string',
            'FECHA_HORA_CREACION'=>'xsd:string',
            'CATEGORIA_ID'=>'xsd:int'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta un articulo en el sistema');

        $server->register(
            'ActualizaArticulo',
            array(
                'ARTICULO_ID'=>'xsd:int',
                'MARCA_ID'=>'xsd:int',
                'NOMBRE'=>'xsd:string',
                'PRECIO'=>'xsd:string',
                'DESCRIPCION'=>'xsd:string',
                'IMAGEN'=>'xsd:string',
                'USUARIO_MODIFICACION'=>'xsd:string',
                'FECHA_HORA_MODIFICACION'=>'xsd:string',
                'CATEGORIA_ID'=>'xsd:int'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza el articulo en el sistema');
                 
        $server->wsdl->addComplexType(
            'MostrarArticulos',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'ARTICULO_ID' => array('name'=>'ARTICULO_ID','type'=>'xsd:int'),
                'MARCA_ID' => array('name'=>'MARCA_ID','type'=>'xsd:int'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'PRECIO' => array('name'=>'PRECIO','type'=>'xsd:string'),
                'SKU' => array('name'=>'SKU','type'=>'xsd:string'),
                'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
                'USUARIO_CREADOR' => array('name'=>'USUARIO_CREADOR','type'=>'xsd:string'),
                'FECHA_HORA_CREACION' => array('name'=>'FECHA_HORA_CREACION','type'=>'xsd:string'),
                'USUARIO_MODIFICACION' => array('name'=>'USUARIO_MODIFICACION','type'=>'xsd:string'),
                'FECHA_HORA_MODIFICACION' => array('name'=>'FECHA_HORA_MODIFICACION','type'=>'xsd:string'),
                'CATEGORIA_ID' => array('name'=>'CATEGORIA_ID','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarArticulosArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarArticulos[]')),
            'tns:MostrarArticulos'
            );
        $server->register(
            'MostrarArticulos',
            array(
                'ARTICULO_ID'=>'xsd:int',
                'MARCA_ID'=>'xsd:int',
                'CATEGORIA_ID'=>'xsd:int'
            ),
            array('return'=> 'tns:MostrarArticulosArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los articulos del sistema (usando "0" en articulo id, marca id y categoría id muestra todos los articulos del sistema)');