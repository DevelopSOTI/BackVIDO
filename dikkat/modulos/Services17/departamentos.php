<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los departamentos del sistema">
    function InsertarDepartamento($NOMBRE,$CLAVE){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE DEPARTAMENTOS">                 
                      $query   ="INSERT INTO DEPARTAMENTOS (NOMBRE,CLAVE) ";
                      $query  .="VALUES('$NOMBRE','$CLAVE');";                 
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
  // <editor-fold defaultstate="collapsed" desc="Actualiza el departamento en el sistema">
  function ActualizaDepartamento($DEPARTAMENTO_ID,$NOMBRE,$CLAVE){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DEL DEPARTAMENTO">                 
                  $query  = "UPDATE DEPARTAMENTOS ";
                  $query .= "SET ";
                  $query .= "NOMBRE = '$NOMBRE', ";
                  $query .= "CLAVE = '$CLAVE' ";
                  $query .= "WHERE DEPARTAMENTO_ID =$DEPARTAMENTO_ID ; ";                 
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
// <editor-fold defaultstate="collapsed" desc="Muestra los datos de los departamentos del sistema">
function MostrarDepartamentos(){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LA MARCA EN EL SISTEMA">                 
                  $select  = "SELECT DEPARTAMENTO_ID, NOMBRE, CLAVE FROM DEPARTAMENTOS;";
                               
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        return $stmt->fetch_all(MYSQLI_ASSOC); 
                    }
                    else{
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
// <editor-fold defaultstate="collapsed" desc="Inserta los departamentos a las sucursales del sistema">
function InsertarDepartamentoSucursal($DEPARTAMENTO_ID,$SUCURSAL_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="INSERSION DE DEPARTAMENTOS EN LAS SUCURSALES">                 
                  $query   ="INSERT INTO DEPARTAMENTOS_SUSCURSALES(DEPARTAMENTO_ID,SUCURSAL_ID,ESTATUS) ";
                  $query  .="VALUES('$DEPARTAMENTO_ID','$SUCURSAL_ID','A');";                 
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
  // <editor-fold defaultstate="collapsed" desc="Actualiza el departamento en el sistema">
  function ActualizaEstatusDepartamentoSucursal($DEPARTAMENTO_ID,$SUCURSAL_ID,$ESTATUS){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DEL DEPARTAMENTO">                 
                  $query  = "UPDATE DEPARTAMENTOS_SUSCURSALES SET ESTATUS = '$ESTATUS'";
                  $query .= " WHERE DEPARTAMENTO_ID = $DEPARTAMENTO_ID AND SUCURSAL_ID = $SUCURSAL_ID;";                 
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
// <editor-fold defaultstate="collapsed" desc="Muestra los datos de los departamentos del sistema">
function MostrarDepartamentosSucursal($SUCURSAL_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LA MARCA EN EL SISTEMA">                 
                  $select  = "SELECT D.DEPARTAMENTO_ID,D.NOMBRE ,D.CLAVE FROM soticomm_INMEX.DEPARTAMENTOS_SUSCURSALES AS DS ";
                  $select .= " INNER JOIN soticomm_INMEX.DEPARTAMENTOS AS D ON DS.DEPARTAMENTO_ID=D.DEPARTAMENTO_ID";
                  $select .= " WHERE DS.SUCURSAL_ID=$SUCURSAL_ID";
                               
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $roles["DEPARTAMENTO_ID"]=$row["DEPARTAMENTO_ID"];
                            $roles["NOMBRE"]=$row["NOMBRE"];
                            $roles["CLAVE"]=$row["CLAVE"];
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
        'InsertarDepartamento',
        array(
            'NOMBRE'=>'xsd:string',
            'CLAVE'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta un departamento en el sistema');

    $server->register(
        'ActualizaDepartamento',
        array(
            'DEPARTAMENTO_ID'=>'xsd:int',
            'NOMBRE'=>'xsd:string',
            'CLAVE'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza la marca indicada en el sistema');

        $server->wsdl->addComplexType(
            'MostrarDepartamentos',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'DEPARTAMENTO_ID' => array('name'=>'DEPARTAMENTO_ID','type'=>'xsd:int'),
                'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarDepartamentosArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarDepartamentos[]')),
            'tns:MostrarDepartamentos'
            );
        $server->register(
            'MostrarDepartamentos',
            array(),
            array('return'=> 'tns:MostrarDepartamentosArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los datos de los departamentos del sistema');

            $server->register(
                'InsertarDepartamentoSucursal',
                array(
                    'DEPARTAMENTO_ID'=>'xsd:int',
                    'SUCURSAL_ID'=>'xsd:int'
                    ),
                array('return'=>'xsd:boolean'),
                $namespace,
                false,
                'rpc',
                false,
                'Inserta un departamento a la sucursal en el sistema');
                $server->register(
                    'ActualizaEstatusDepartamentoSucursal',
                    array(
                        'DEPARTAMENTO_ID'=>'xsd:int',
                        'SUCURSAL_ID'=>'xsd:int',
                        'ESTATUS'=>'xsd:string'
                        ),
                    array('return'=>'xsd:boolean'),
                    $namespace,
                    false,
                    'rpc',
                    false,
                    'Actualiza el estatus del departamento en el sistema de la sucursal especificada');

                    $server->wsdl->addComplexType(
                        'MostrarDepartamentosSucursal',
                        'complexType',
                        'struct',
                        'all',
                        '',
                        array(
                            'DEPARTAMENTO_ID' => array('name'=>'DEPARTAMENTO_ID','type'=>'xsd:int'),
                            'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                            'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
                        )
                    );    
                    $server->wsdl->addComplexType(
                        'MostrarDepartamentosSucursalArray',
                        'complexType',
                        'array',
                        '',
                        'SOAP-ENC:Array',
                        array(),
                        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarDepartamentosSucursal[]')),
                        'tns:MostrarDepartamentosSucursal'
                        );
                    $server->register(
                        'MostrarDepartamentosSucursal',
                        array('SUCURSAL_ID'=>'xsd:int'),
                        array('return'=> 'tns:MostrarDepartamentosSucursalArray'),
                        $namespace,
                        false,
                        'rpc',
                        false,
                        'Devuelve un arreglo con los roles del usuario indicado indicada');

