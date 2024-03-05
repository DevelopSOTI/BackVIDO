<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los dias de la semana que asistirá un promotor a una sucursal">
function InsertarSucursalProveedorDias($SUCURSAL_ID,$PROVEEDOR_ID,$LUN,$MAR,$MIE,$JUE,$VIE,$SAB,$DOM){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="INSERSION DE DIAS DE LA SEMANA QUE ASISTIRÁ UN PROMOTOR A UNA SUCURSAL">                 
                  $query  ="INSERT INTO SUCURSALES_PROVEEDORES_DIAS (SUCURSAL_ID,PROVEEDOR_ID,LUN,MAR,MIE,JUE,VIE,SAB,DOM,ESTATUS) ";
                  $query .="VALUES ($SUCURSAL_ID,$PROVEEDOR_ID,'$LUN','$MAR','$MIE','$JUE','$VIE','$SAB','$DOM','A');";                 
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
// <editor-fold defaultstate="collapsed" desc="Actualiza los dias de la semana que asistirá un promotor a una sucursal">
function ActualizaSucursalProveedorDias($SUCURSAL_ID,$PROVEEDOR_ID,$LUN,$MAR,$MIE,$JUE,$VIE,$SAB,$DOM,$ESTATUS){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE DIAS DE LA SEMANA QUE ASISTIRÁ UN PROMOTOR A UNA SUCURSAL">                 
                  $query  = " UPDATE SUCURSALES_PROVEEDORES_DIAS ";
                  $query .= " SET ";
                  $query .= " LUN = '$LUN', ";
                  $query .= " MAR = '$MAR', ";
                  $query .= " MIE = '$MIE', ";
                  $query .= " JUE = '$JUE', ";
                  $query .= " VIE = '$VIE', ";
                  $query .= " SAB = '$SAB', ";
                  $query .= " DOM = '$DOM', ";
                  $query .= " ESTATUS = '$ESTATUS' ";
                  $query .= " WHERE  ";
                  $query .= " SUCURSAL_ID = $SUCURSAL_ID AND PROVEEDOR_ID = $PROVEEDOR_ID";                 
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
function MostrarSucursalProveedorDias($SUCURSAL_ID,$PROVEEDOR_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=null;
    if ($conn){                             
            $select  ="SELECT LUN,MAR,MIE,JUE,VIE,SAB,DOM FROM soticomm_INMEX.SUCURSALES_PROVEEDORES_DIAS ";
            $select .=" WHERE SUCURSAL_ID=$SUCURSAL_ID AND PROVEEDOR_ID=$PROVEEDOR_ID AND ESTATUS='A';";     
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $sucursales["LUN"] = $row["LUN"];
                 $sucursales["MAR"] = $row["MAR"];
                 $sucursales["MIE"] = $row["MIE"];
                 $sucursales["JUE"] = $row["JUE"]; 
                 $sucursales["VIE"] = $row["VIE"];
                 $sucursales["SAB"] = $row["SAB"];
                 $sucursales["DOM"] = $row["DOM"];
                 $result[]=$sucursales;
            }     
            mysqli_close($conn);
        }
        else{
            
            mysqli_close($conn);
            return null;
        }
    }
    else {
        // FALLO LA CONEXION
        return null;
    }
    return $result;
}

$server->register(
    'InsertarSucursalProveedorDias',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'PROVEEDOR_ID'=>'xsd:int',
        'LUN'=>'xsd:string',
        'MAR'=>'xsd:string',
        'MIE'=>'xsd:string',
        'JUE'=>'xsd:string',
        'VIE'=>'xsd:string',
        'SAB'=>'xsd:string',
        'DOM'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta los dias de la semana que asistirá un proveedor a una sucursal');

$server->register(
    'ActualizaSucursalProveedorDias',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'PROVEEDOR_ID'=>'xsd:int',
        'LUN'=>'xsd:string',
        'MAR'=>'xsd:string',
        'MIE'=>'xsd:string',
        'JUE'=>'xsd:string',
        'VIE'=>'xsd:string',
        'SAB'=>'xsd:string',
        'DOM'=>'xsd:string',
        'ESTATUS'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la sucursal indicada en el sistema');
    
    $server->wsdl->addComplexType(
        'MostrarSucursalProveedorDias',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'LUN' => array('name'=>'LUN','type'=>'xsd:string'),
            'MAR' => array('name'=>'MAR','type'=>'xsd:string'),
            'MIE' => array('name'=>'MIE','type'=>'xsd:string'),
            'JUE' => array('name'=>'JUE','type'=>'xsd:string'),
            'VIE' => array('name'=>'VIE','type'=>'xsd:string'),
            'SAB' => array('name'=>'SAB','type'=>'xsd:string'),
            'DOM' => array('name'=>'DOM','type'=>'xsd:string')
        )
    );    
    $server->wsdl->addComplexType(
        'MostrarSucursalProveedorDiasArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarSucursalProveedorDias[]')),
        'tns:MostrarSucursalProveedorDias'
        );
        $server->register(
            'MostrarSucursalProveedorDias',
            array(
                'SUCURSAL_ID'=>'xsd:int',
                'PROVEEDOR_ID'=>'xsd:int'
            ),
            array('return'=> 'tns:MostrarSucursalProveedorDiasArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con las sucursales de un proveedor especificado');