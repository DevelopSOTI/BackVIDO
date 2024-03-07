<?php
function SucursalesUsuario($USUARIO,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);//ESTABA DBKLYNS Y LO CAMBIE A BD
    $result=null;
    if ($conn){  
        $sucursalesid="";
            $select  ="SELECT S.NOMBRE,S.LAT_LONG,S.GEO_CERCA,S.SUCURSAL_ID ";
            $select .=" FROM USUARIOS_SUCURSAL as US ";
            $select .=" INNER JOIN USUARIOS_SUCURSAL_DET AS USD ON US.USUARIO_SUCURSAL_ID = USD.USUARIO_SUCURSAL_ID ";
            $select .=" INNER JOIN USUARIOS AS U ON USD.USUARIO_ID=U.USUARIO_ID ";
            $select .=" INNER JOIN SUCURSALES AS S ON USD.SUCURSAL_ID=S.SUCURSAL_ID ";
            $select .=" WHERE ";
            $select .=" /*(current_date() BETWEEN FECHA_INI AND FECHA_FIN) AND*/ (US.ESTATUS='A') AND (U.USUARIO='$USUARIO');";     
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
               $sucursales["NOMBRE"] = $row["NOMBRE"];
               $sucursales["LAT_LONG"]= $row["LAT_LONG"];
               $sucursales["GEO_CERCA"] = $row["GEO_CERCA"];
               $sucursales["SUCURSAL_ID"] = $row["SUCURSAL_ID"];
               $sucursalesid.=$row["SUCURSAL_ID"].",";
               $result[]=$sucursales;
            }   
            //echo $sucursalesid;
          /*  $sucursalesid=substr($sucursalesid,0,strlen($sucursalesid)-1);
            //echo $sucursalesid;
             $select  = "SELECT S.NOMBRE,S.LAT_LONG,S.GEO_CERCA,S.SUCURSAL_ID ";
            $select .= " FROM CHEQUEO_COMPETENCIA as CC ";
            $select .= " INNER JOIN SUCURSALES_CHEQUEO_COMPETENCIA AS SCC ON CC.CHEQUEO_COMPETENCIA_ID=SCC.CHEQUEO_COMPETENCIA_ID ";
            $select .= " INNER JOIN SUCURSALES AS S ON SCC.SUCURSAL_ID=S.SUCURSAL_ID ";
            $select .= " INNER JOIN USUARIOS AS U ON CC.USUARIO_ASIGNADO=U.USUARIO ";
            $select .= " WHERE ";
            $select .= " (FECHA_REVISION=cast(current_date() as date))AND (CC.ESTATUS='A') AND (U.USUARIO='$USUARIO')AND S.SUCURSAL_ID NOT IN ($sucursalesid);";  
            
            //echo $select;
            $stmt = mysqli_query($conn, $select); 
            if ($stmt){
                while ($row = mysqli_fetch_assoc($stmt)){
                    
                  $sucursales["NOMBRE"] = $row["NOMBRE"];
                  $sucursales["LAT_LONG"]= $row["LAT_LONG"];
                  $sucursales["GEO_CERCA"] = $row["GEO_CERCA"];
                  $sucursales["SUCURSAL_ID"] = $row["SUCURSAL_ID"];
                  $result[]=$sucursales;
               }  
            }*/
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

function SucursalesProgramacionVisitas($SUCURSAL_ID,$FECHA_PROGRAMACION,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result=null;
    if ($conn){                             
            $select  ="SELECT P.NOMBRE,PVD.PROGRAMACION_VISITAS_DET_ID,PC.CLAVE,P.PROVEEDOR_ID,PV.PROGRAMACION_VISITAS_ID ";
            $select .=" FROM PROGRAMACION_VISITAS as PV ";
            $select .=" INNER JOIN PROGRAMACION_VISITAS_DET AS PVD ON PV.PROGRAMACION_VISITAS_ID=PVD.PROGRAMACION_VISITAS_ID ";
            $select .=" INNER JOIN PROVEEDORES AS P ON PVD.PROVEEDOR_ID=P.PROVEEDOR_ID ";
            $select .=" INNER JOIN PROVEEDORES_CLIENTES AS PC ON P.PROVEEDOR_ID=PC.PROVEEDOR_ID";
            $select .=" WHERE PVD.SUCURSAL_ID =$SUCURSAL_ID AND PV.FECHA_PROGRAMACION ='$FECHA_PROGRAMACION' ";
            $select .=" ORDER BY P.NOMBRE DESC;";     
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                $visita["NOMBRE"] = $row["NOMBRE"];
                $visita["PROGRAMACION_VISITAS_DET_ID"]= $row["PROGRAMACION_VISITAS_DET_ID"];
                $visita["CLAVE"]= $row["CLAVE"];
                $visita["PROVEEDOR_ID"]= $row["PROVEEDOR_ID"];
                $result[]=$visita;
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
function MostrarTodasSucursales($BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result=null;
    if ($conn){                             
            $select  ="SELECT S.NOMBRE,S.LAT_LONG,S.GEO_CERCA,S.SUCURSAL_ID ";
            $select .=" FROM SUCURSALES AS S";     
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
               $sucursales["NOMBRE"] = $row["NOMBRE"];
               $sucursales["LAT_LONG"]= $row["LAT_LONG"];
               $sucursales["GEO_CERCA"] = $row["GEO_CERCA"];
               $sucursales["SUCURSAL_ID"] = $row["SUCURSAL_ID"];
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


$server->wsdl->addComplexType(
    'SucursalesUsuario',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
        'LAT_LONG' => array('name'=>'LAT_LONG','type'=>'xsd:string'),
        'GEO_CERCA' => array('name'=>'GEO_CERCA','type'=>'xsd:string'),
        'SUCURSAL_ID' => array('name'=>'SUCURSAL_ID','type'=>'xsd:string')
    )
);    
$server->wsdl->addComplexType(
    'SucursalesUsuarioArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:SucursalesUsuario[]')),
    'tns:SucursalesUsuario'
    );
$server->register(
    'SucursalesUsuario',
    array('USUARIO'=>'xsd:string',
          'BD'=>'xsd:string'),
    array('return'=> 'tns:SucursalesUsuarioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con las sucursales del usuario'); 
    
    
    $server->wsdl->addComplexType(
        'SucursalesProgramacionVisitas',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
            'PROGRAMACION_VISITAS_DET_ID' => array('name'=>'PROGRAMACION_VISITAS_DET_ID','type'=>'xsd:string'),
            'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string'),
            'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:string'),
        )
    );    
    $server->wsdl->addComplexType(
        'SucursalesProgramacionVisitasArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:SucursalesProgramacionVisitas[]')),
        'tns:SucursalesProgramacionVisitas'
        );
    $server->register(
        'SucursalesProgramacionVisitas',
        array('SUCURSAL_ID'=>'xsd:int',
              'FECHA_PROGRAMACION'=>'xsd:string',
              'BD'=>'xsd:string'),
        array('return'=> 'tns:SucursalesProgramacionVisitasArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con los proveedores asginados a una sucursal en una fecha determinada');   

        $server->register(
            'MostrarTodasSucursales',
            array('BD'='xsd:string'),
            array('return'=> 'tns:SucursalesUsuarioArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con las sucursales del usuario'); 
