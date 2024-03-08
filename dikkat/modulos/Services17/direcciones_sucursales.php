<?php
// <editor-fold defaultstate="collapsed" desc="Inserta las sucursales del sistema">
    function InsertarDireccionSucursal($NOMBRE,$CALLE,$COLONIA,$NUM_EXT,$NUM_INT,$RFC,$ESTADO,$PAIS,$CIUDAD,$COD_POSTAL,$CORREOS,$NUM_TELEFONO,$REFERENCIA,$BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERCION DE SUCURSALES">                 
                      $query  ="INSERT INTO DIR_SUCURSALES ";
                      $query  .="( ";
                      $query  .="NOMBRE, ";
                      $query  .="CALLE, ";
                      $query  .="COLONIA, ";
                      $query  .="NUM_EXT, ";
                      $query  .="NUM_INT, ";
                      $query  .="RFC, ";
                      $query  .="ESTADO, ";
                      $query  .="PAIS, ";
                      $query  .="CIUDAD, ";
                      $query  .="COD_POSTAL, ";
                      $query  .="CORREOS, ";
                      $query  .="NUM_TELEFONO, ";
                      $query  .="REFERENCIA) ";
                      $query  .="VALUES ";
                      $query  .="( ";
                      $query  .="'$NOMBRE', ";
                      $query  .="'$CALLE', ";
                      $query  .="'$COLONIA', ";
                      $query  .="'$NUM_EXT', ";
                      $query  .="'$NUM_INT', ";
                      $query  .="'$RFC', ";
                      $query  .="'$ESTADO', ";
                      $query  .="'$PAIS', ";
                      $query  .="'$CIUDAD', ";
                      $query  .="'$COD_POSTAL', ";
                      $query  .="'$CORREOS', ";
                      $query  .="'$NUM_TELEFONO', ";
                      $query  .="'$REFERENCIA');";                 
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
// <editor-fold defaultstate="collapsed" desc="Actualiza las sucursales del sistema">
function ActualizaDireccionSucursal($DIR_SUC_ID,$NOMBRE,$CALLE,$COLONIA,$NUM_EXT,$NUM_INT,$RFC,$ESTADO,$PAIS,$CIUDAD,$COD_POSTAL,$CORREOS,$NUM_TELEFONO,$REFERENCIA,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LA DIRECCION DE LA SUCURSAL">                 
                  $query  = "UPDATE DIR_SUCURSALES";
                  $query  .="SET";
                  $query  .="NOMBRE = '$NOMBRE',";
                  $query  .="CALLE = '$CALLE',";
                  $query  .="COLONIA = '$COLONIA',";
                  $query  .="NUM_EXT = '$NUM_EXT',";
                  $query  .="NUM_INT = '$NUM_INT',";
                  $query  .="RFC = '$RFC',";
                  $query  .="ESTADO = '$ESTADO',";
                  $query  .="PAIS = '$PAIS',";
                  $query  .="CIUDAD = '$CIUDAD',";
                  $query  .="COD_POSTAL = '$COD_POSTAL',";
                  $query  .="CORREOS = '$CORREOS',";
                  $query  .="NUM_TELEFONO = '$NUM_TELEFONO',";
                  $query  .="REFERENCIA = '$REFERENCIA'";
                  $query  .="WHERE DIR_SUC_ID = $DIR_SUC_ID;";                 
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
    // <editor-fold defaultstate="collapsed" desc="Muestra todas las sucursales por cliente">
    function MostrarDireccionSucursal($DIR_SUC_ID,$BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
        $result = false;
        if ($conn){  
                    // <editor-fold defaultstate="collapsed" desc="SELECCION DE TODOS LAS SUCURSALES POR CLIENTE">                 
                      $select  = "SELECT DIR_SUC_ID, NOMBRE, CALLE, COLONIA, NUM_EXT, NUM_INT, RFC, ESTADO, PAIS, CIUDAD, COD_POSTAL";
                      $select .= ", CORREOS, NUM_TELEFONO, REFERENCIA FROM DIR_SUCURSALES WHERE DIR_SUC_ID =$DIR_SUC_ID;";                 
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
        'InsertarDireccionSucursal',
        array(           
            'NOMBRE'=>'xsd:string',
            'CALLE'=>'xsd:string',
            'COLONIA'=>'xsd:string',
            'NUM_EXT'=>'xsd:string',
            'NUM_INT'=>'xsd:string',
            'RFC'=>'xsd:string',
            'ESTADO'=>'xsd:string',
            'PAIS'=>'xsd:string',
            'CIUDAD'=>'xsd:string',
            'COD_POSTAL'=>'xsd:string',
            'CORREOS'=>'xsd:string',
            'NUM_TELEFONO'=>'xsd:string',
            'REFERENCIA'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta la direccion de la sucursal en el sistema');

    $server->register(
        'ActualizaDireccionSucursal',
        array(
            'DIR_SUC_ID'=>'xsd:int',
            'NOMBRE'=>'xsd:string',
            'CALLE'=>'xsd:string',
            'COLONIA'=>'xsd:string',
            'NUM_EXT'=>'xsd:string',
            'NUM_INT'=>'xsd:string',
            'RFC'=>'xsd:string',
            'ESTADO'=>'xsd:string',
            'PAIS'=>'xsd:string',
            'CIUDAD'=>'xsd:string',
            'COD_POSTAL'=>'xsd:string',
            'CORREOS'=>'xsd:string',
            'NUM_TELEFONO'=>'xsd:string',
            'REFERENCIA'=>'xsd:string',
            'BD'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la direccion de la sucursal indicada en el sistema');

            $server->wsdl->addComplexType(
                'MostrarDireccionSucursal',
                'complexType',
                'struct',
                'all',
                '',
                array(
                    'DIR_SUC_ID' => array('name'=>'DIR_SUC_ID','type'=>'xsd:int'),
                    'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                    'CALLE' => array('name'=>'CALLE','type'=>'xsd:string'),
                    'COLONIA' => array('name'=>'COLONIA','type'=>'xsd:string'),
                    'NUM_EXT' => array('name'=>'NUM_EXT','type'=>'xsd:string'),
                    'NUM_INT' => array('name'=>'NUM_INT','type'=>'xsd:string'),
                    'RFC' => array('name'=>'RFC','type'=>'xsd:string'),
                    'ESTADO' => array('name'=>'ESTADO','type'=>'xsd:string'),
                    'PAIS' => array('name'=>'PAIS','type'=>'xsd:string'),
                    'CIUDAD' => array('name'=>'CIUDAD','type'=>'xsd:string'),
                    'COD_POSTAL' => array('name'=>'COD_POSTAL','type'=>'xsd:string'),
                    'CORREOS' => array('name'=>'CORREOS','type'=>'xsd:string'),
                    'NUM_TELEFONO' => array('name'=>'NUM_TELEFONO','type'=>'xsd:string'),
                    'REFERENCIA' => array('name'=>'REFERENCIA','type'=>'xsd:string')                    
                )
            );    
            $server->wsdl->addComplexType(
                'MostrarDireccionSucursalArray',
                'complexType',
                'array',
                '',
                'SOAP-ENC:Array',
                array(),
                array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarDireccionSucursal[]')),
                'tns:MostrarDireccionSucursal'
                );
                $server->register(
                    'MostrarDireccionSucursal',
                    array(
                        'DIR_SUC_ID'=>'xsd:int',
                        'BD'=>'xsd:string'
                         ),
                    array('return'=> 'tns:MostrarDireccionSucursalArray'),
                    $namespace,
                    false,
                    'rpc',
                    false,
                    'Devuelve un arreglo con los datos de la sucursal especificada');