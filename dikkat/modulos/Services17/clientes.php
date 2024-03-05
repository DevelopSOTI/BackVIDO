<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los clientes del sistema">
    function InsertarCliente($NOMBRE,$RFC,$CLAVE,$DIRECCION,$NUM_EXT,$NUM_INT,$TELEFONO,$USUARIO_CREADOR,$FECHA_HORA_CREACION){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE CLIENTES">                 
                      $query   ="INSERT INTO CLIENTES (NOMBRE,RFC,CLAVE,DIRECCION,NUM_EXT,NUM_INT,TELEFONO,USUARIO_CREADOR,FECHA_HORA_CREACION)";
                      $query  .= "VALUES('$NOMBRE','$RFC','$CLAVE','$DIRECCION','$NUM_EXT','$NUM_INT','$TELEFONO','$USUARIO_CREADOR','$FECHA_HORA_CREACION');";                 
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
 // <editor-fold defaultstate="collapsed" desc="Actualiza el cliente del sistema">
 function ActualizaCliente($CLIENTE_ID,$NOMBRE,$RFC,$CLAVE,$DIRECCION,$NUM_EXT,$NUM_INT,$TELEFONO,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DEL CLIENTE">                 
                  $query  = "UPDATE CLIENTES";
                  $query  .= " SET";
                  $query  .= " NOMBRE = '$NOMBRE',";
                  $query  .= " RFC = '$RFC',";
                  $query  .= " CLAVE = '$CLAVE',";
                  $query  .= " DIRECCION = '$DIRECCION',";
                  $query  .= " NUM_EXT = '$NUM_EXT',";
                  $query  .= " NUM_INT = '$NUM_INT',";
                  $query  .= " TELEFONO = '$TELEFONO',";
                  $query  .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION',";
                  $query  .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION'";
                  $query  .= " WHERE CLIENTE_ID = $CLIENTE_ID;";                 
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
 // <editor-fold defaultstate="collapsed" desc="Muestra los datos del cliente del sistema">
 function MostrarCliente($CLIENTE_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DEL CILETE EN EL SISTEMA">                 
                  $select  = "SELECT CLIENTE_ID,NOMBRE,RFC,CLAVE,DIRECCION,NUM_EXT,NUM_INT,TELEFONO,USUARIO_CREADOR,FECHA_HORA_CREACION,USUARIO_MODIFICACION,FECHA_HORA_MODIFICACION FROM CLIENTES ";
                  if($CLIENTE_ID>0)
                  {
                      $select  .= " WHERE CLIENTE_ID=$CLIENTE_ID;";      
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
        'InsertarCliente',
        array(
            'NOMBRE'=>'xsd:string',
            'RFC'=>'xsd:string',
            'CLAVE'=>'xsd:string',
            'DIRECCION'=>'xsd:string',
            'NUM_EXT'=>'xsd:string',
            'NUM_INT'=>'xsd:string',
            'TELEFONO'=>'xsd:string',
            'USUARIOCREADOR'=>'xsd:string',
            'FECHACREACION'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta el cliente en el sistema');

    $server->register(
        'ActualizarCliente',
        array(
            'CLIENTE_ID'=>'xsd:int',
            'NOMBRE'=>'xsd:string',
            'RFC'=>'xsd:string',
            'CLAVE'=>'xsd:string',
            'DIRECCION'=>'xsd:string',
            'NUM_EXT'=>'xsd:string',
            'NUM_INT'=>'xsd:string',
            'TELEFONO'=>'xsd:string',
            'USUARIOMODIFICACION'=>'xsd:string',
            'FECHAMODIFICACION'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza el usuario indicado en el sistema');

        $server->wsdl->addComplexType(
            'MostrarCliente',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'CLIENTE_ID' => array('name'=>'CLIENTE_ID','type'=>'xsd:int'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'RFC' => array('name'=>'RFC','type'=>'xsd:string'),
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string'),
                'DIRECCION' => array('name'=>'DIRECCION','type'=>'xsd:string'),
                'NUM_EXT' => array('name'=>'NUM_EXT','type'=>'xsd:string'),
                'NUM_INT' => array('name'=>'NUM_INT','type'=>'xsd:string'),
                'TELEFONO' => array('name'=>'TELEFONO','type'=>'xsd:string'),
                'USUARIO_CREADOR' => array('name'=>'USUARIO_CREADOR','type'=>'xsd:string'),
                'FECHA_HORA_CREACION' => array('name'=>'FECHA_HORA_CREACION','type'=>'xsd:string'),
                'USUARIO_MODIFICACION' => array('name'=>'USUARIO_MODIFICACION','type'=>'xsd:string'),
                'FECHA_HORA_MODIFICACION' => array('name'=>'FECHA_HORA_MODIFICACION','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarClienteArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarCliente[]')),
            'tns:MostrarCliente'
            );
            $server->register(
                'MostrarCliente',
                array('CLIENTE_ID'=>'xsd:int'),
                array('return'=> 'tns:MostrarClienteArray'),
                $namespace,
                false,
                'rpc',
                false,
                'Devuelve un arreglo con los datos del Cliente');