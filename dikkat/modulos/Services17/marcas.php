<?php
// <editor-fold defaultstate="collapsed" desc="Inserta las marcas del sistema">
    function InsertarMarca($NOMBRE,$CLAVE,$USUARIO_CREADOR,$FECHA_HORA_CREACION){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE MARCAS">                 
                      $query   ="INSERT INTO MARCAS (NOMBRE,CLAVE,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
                      $query   .="VALUES('$NOMBRE','$CLAVE','$USUARIO_CREADOR','$FECHA_HORA_CREACION');";                 
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

     // <editor-fold defaultstate="collapsed" desc="Actualiza la marca en el sistema">
 function ActualizaMarca($MARCA_ID,$NOMBRE,$CLAVE,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LA MARCA">                 
                  $query  = "UPDATE MARCAS ";
                  $query  .= " SET ";
                  $query  .= " MARCA_ID = '$MARCA_ID', ";
                  $query  .= " NOMBRE = '$NOMBRE', ";
                  $query  .= " CLAVE = '$CLAVE', ";
                  $query  .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                  $query  .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
                  $query  .= " WHERE MARCA_ID = '$MARCA_ID'; ";                 
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

 // <editor-fold defaultstate="collapsed" desc="Muestra los datos de la marca indicada en el sistema">
 function MostrarMarca($MARCA_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LA MARCA EN EL SISTEMA">                 
                  $select  = "SELECT MARCA_ID, NOMBRE, CLAVE, USUARIO_CREADOR, FECHA_HORA_CREACION, USUARIO_MODIFICACION, FECHA_HORA_MODIFICACION FROM MARCAS";
                  if($MARCA_ID>0){
                    $select  .= " WHERE MARCA_ID=$MARCA_ID;";  
                  }               
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        
                    mysqli_close($conn);
                        return $stmt->fetch_all(MYSQLI_ASSOC); 
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
        'InsertarMarca',
        array(
            'NOMBRE'=>'xsd:string',
            'CLAVE'=>'xsd:string',
            'USUARIO_CREADOR'=>'xsd:string',
            'FECHA_HORA_CREACION'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta marca en el sistema');

        $server->register(
            'ActualizaMarca',
            array(
                'MARCA_ID'=>'xsd:int',
                'NOMBRE'=>'xsd:string',
                'CLAVE'=>'xsd:string',
                'USUARIO_MODIFICACION'=>'xsd:string',
                'FECHA_HORA_MODIFICACION'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la marca indicada en el sistema');
 
            $server->wsdl->addComplexType(
                'MostrarMarca',
                'complexType',
                'struct',
                'all',
                '',
                array(
                    'MARCA_ID' => array('name'=>'MARCA_ID','type'=>'xsd:int'),
                    'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
                    'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string'),
                    'USUARIO_CREADOR' => array('name'=>'USUARIO_CREADOR','type'=>'xsd:string'),
                    'FECHA_HORA_CREACION' => array('name'=>'FECHA_HORA_CREACION','type'=>'xsd:string'),
                    'USUARIO_MODIFICACION' => array('name'=>'USUARIO_MODIFICACION','type'=>'xsd:string'),
                    'FECHA_HORA_MODIFICACION' => array('name'=>'FECHA_HORA_MODIFICACION','type'=>'xsd:string')
                )
            );    
            $server->wsdl->addComplexType(
                'MostrarMarcaArray',
                'complexType',
                'array',
                '',
                'SOAP-ENC:Array',
                array(),
                array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarMarca[]')),
                'tns:MostrarMarca'
                );
            $server->register(
                'MostrarMarca',
                array('MARCA_ID'=>'xsd:int'),
                array('return'=> 'tns:MostrarMarcaArray'),
                $namespace,
                false,
                'rpc',
                false,
                'Devuelve un arreglo con los datos de la marca indicada');           