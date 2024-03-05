<?php
function ResumenCaducidades($SUCURSAL_ID,$FECHA,$CADUCIDAD_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){ 
        $select  = "SELECT C.NOMBRE, CC.FECHA_REVISION FROM CLIENTES C ";
        $select .= " JOIN CADUCIDADES CC ON CC.CLIENTE_ID = C.CLIENTE_ID ";
        $select .= " WHERE CC.CADUCIDAD_ID = '$CADUCIDAD_ID';";
        $stmt = mysqli_query($conn, $select);

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){                
                $resumen["NOMBRE"]                =$row["NOMBRE"];
                $resumen["FECHA_REVISION"]        =$row["FECHA_REVISION"];
                //$result[]=$resumen;
            } 
        }
        $select  = "SELECT CDS.HORA_FIN, CDS.HORA_INICIO, TIMEDIFF(CDS.HORA_FIN, CDS.HORA_INICIO) AS TIEMPO_DIF ";
        $select .= " FROM CADUCIDADES_DETALLE_SUCURSALES CDS JOIN CADUCIDADES C ON C.CADUCIDAD_ID = CDS.CADUCIDADES_ID ";
        $select .= " WHERE C.CADUCIDAD_ID = '$CADUCIDAD_ID' AND CDS.SUCURSAL_ID=$SUCURSAL_ID;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){                
                $resumen["HORA_FIN"]      =$row["HORA_FIN"];
                $resumen["HORA_INICIO"]   =$row["HORA_INICIO"];
                $resumen["TIEMPO_DIF"]    =$row["TIEMPO_DIF"];
                //$result[]=$resumen;
            } 
        }
        $select  = "SELECT group_concat(DISTINCT(D.NOMBRE) ) AS CATEGORIAS ";
        $select .= " FROM CADUCIDADES_DETALLE CD ";
        $select .= " JOIN CADUCIDADES C ON C.CADUCIDAD_ID = CD.CADUCIDAD_ID ";
        $select .= " JOIN ARTICULOS A ON A.ARTICULO_ID = CD.ARTICULO_ID ";
        $select .= " JOIN CATEGORIAS CT ON CT.CATEGORIA_ID = A.CATEGORIA_ID ";
        $select .= " INNER JOIN DEPARTAMENTOS AS D ON CT.DEPARTAMENTO_ID=D.DEPARTAMENTO_ID ";
        $select .= " WHERE CD.CADUCIDAD_ID = '$CADUCIDAD_ID' AND CD.SUCURSAL_ID=$SUCURSAL_ID;";
        
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){                
                $resumen["CATEGORIAS"]  =$row["CATEGORIAS"];
                //$result[]=$resumen;
            } 
        }
        $select  = "SELECT COUNT(distinct CD.ARTICULO_ID) AS CODIGOS_ASIGNADOS ";
        $select .= " FROM CADUCIDADES_DETALLE CD ";
        $select .= " JOIN CADUCIDADES C ON CD.CADUCIDAD_ID = C.CADUCIDAD_ID  ";
        $select .= " WHERE CD.CADUCIDAD_ID = '$CADUCIDAD_ID' AND CD.SUCURSAL_ID=$SUCURSAL_ID;";
        
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){                
                $resumen["CODIGOS_ASIGNADOS"]  =$row["CODIGOS_ASIGNADOS"];
                //$result[]=$resumen;
            } 
        }
        $select  = "SELECT COUNT(CDF.FECHA_CADUCIDAD) AS FECHAS_REPORTADAS, ";
        $select .= " (SELECT MIN(CDF.FECHA_CADUCIDAD)) AS FECHA_MAS_RECIENTE, ";
        $select .= " (SELECT COUNT(CD2.ARTICULO_ID)  ";
        $select .= " FROM CADUCIDADES_DETALLE CD2  ";
        $select .= " WHERE CD2.PRECIO = 0 AND CD2.EXHIBIDO = 'S' AND CD2.CADUCIDAD_ID = '$CADUCIDAD_ID' AND CD.SUCURSAL_ID=$SUCURSAL_ID) AS ARTICULOS_SIN_PRECIO ";
        $select .= " FROM CADUCIDADES_DETALLE_FECHAS CDF ";
        $select .= " JOIN CADUCIDADES_DETALLE CD ON CD.CADUCIDADES_DETALLE_ID = CDF.CADUCIDADES_DETALLE_ID ";
        $select .= " WHERE  CD.CADUCIDAD_ID = $CADUCIDAD_ID AND CD.SUCURSAL_ID=$SUCURSAL_ID;";
        
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){                
                $resumen["FECHAS_REPORTADAS"]       =$row["FECHAS_REPORTADAS"];
                $resumen["FECHA_MAS_RECIENTE"]      =$row["FECHA_MAS_RECIENTE"];
                $resumen["ARTICULOS_SIN_PRECIO"]    =$row["ARTICULOS_SIN_PRECIO"];
                //$result[]=$resumen;
            }
        } 
        $select  = "SELECT group_concat( DISTINCT(CDF.UBICACION)) as UBICACION FROM CADUCIDADES_DETALLE_FECHAS CDF ";
        $select .= " JOIN CADUCIDADES_DETALLE CD ON CD.CADUCIDADES_DETALLE_ID = CDF.CADUCIDADES_DETALLE_ID ";
        $select .= " WHERE CD.CADUCIDAD_ID = '$CADUCIDAD_ID' AND CD.SUCURSAL_ID=$SUCURSAL_ID;";
        
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){                
                $resumen["UBICACION"]   =$row["UBICACION"];
                // $result[]=$resumen;
            } 
            $select  = "SELECT ifnull(round( SUM(CDF.CANTIDAD * CD.PRECIO),2),0) AS TOTAL_MERMA ";
            $select .= " FROM CADUCIDADES_DETALLE CD ";
            $select .= " JOIN CADUCIDADES_DETALLE_FECHAS CDF ON CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
            $select .= " WHERE CDF.FECHA_CADUCIDAD < '$FECHA' ";
            $select .= " AND CD.ESPACIO_ASIGNADO = 'S' ";
            $select .= " AND CD.EXISTENCIAS = 'S' ";
            $select .= " AND CD.CADUCIDAD_ID = $CADUCIDAD_ID AND CD.SUCURSAL_ID=$SUCURSAL_ID;";
            
            $stmt = mysqli_query($conn, $select);
            if ($stmt){
                while ($row = mysqli_fetch_assoc($stmt)){                
                    $resumen["TOTAL_MERMA"]   =$row["TOTAL_MERMA"];
                    //$result[]=$resumen;
                } 
            }
        }
        $result[]=$resumen;
        mysqli_close($conn);
            return $result;
    }
    else {
        return null; 
    }
}
$server->wsdl->addComplexType(
    'ResumenCaducidades',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
        'FECHA_REVISION' => array('name'=>'FECHA_REVISION','type'=>'xsd:string'),
        'HORA_FIN' => array('name'=>'HORA_FIN','type'=>'xsd:string'),
        'HORA_INICIO' => array('name'=>'HORA_INICIO','type'=>'xsd:string'),
        'TIEMPO_DIF' => array('name'=>'TIEMPO_DIF','type'=>'xsd:string'),
        'CODIGOS_ASIGNADOS' => array('name'=>'CODIGOS_ASIGNADOS','type'=>'xsd:string'),
        'FECHAS_REPORTADAS' => array('name'=>'FECHAS_REPORTADAS','type'=>'xsd:string'),
        'FECHA_MAS_RECIENTE' => array('name'=>'FECHA_MAS_RECIENTE','type'=>'xsd:string'),
        'ARTICULOS_SIN_PRECIO' => array('name'=>'ARTICULOS_SIN_PRECIO','type'=>'xsd:string'),
        'UBICACION' => array('name'=>'UBICACION','type'=>'xsd:string'),
        'TOTAL_MERMA' => array('name'=>'TOTAL_MERMA','type'=>'xsd:string')
    )
    );    
$server->wsdl->addComplexType(
    'ResumenCaducidadesArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:ResumenCaducidades[]')),
    'tns:ResumenCaducidades'
    );
$server->register(
    'ResumenCaducidades',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'FECHA'=>'xsd:string',
        'CADUCIDAD_ID'=>'xsd:int'
    ),
    array('return'=> 'tns:ResumenCaducidadesArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los datos de resumen de caducidades de una fecha y sucursal determinada');

function ResumenProductoPrecio($SUCURSAL_ID,$ASIGNACION_DETALLE_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){ 
        $select   = " select  group_concat( DISTINCT NOMBRE_CATEGORIA) AS NOMBRE_CATEGORIAS,FECHA_CAPTURA,CODIGOS_ASIGNADOS,CODIGO_EXHIBIDO,CODIGO_EXHIBIDO_ETIQUETA,CODIGO_NO_SENALIZADO,DIF_ETIQUETA_VERIFICADOR,DIF_VERIFICADOR_ETIQUETA from ( ";
        $select  .= " SELECT D.NOMBRE AS NOMBRE_CATEGORIA, A.FECHA AS FECHA_CAPTURA, ";
        $select  .= " (SELECT COUNT(AD2.ESPACIO_ASIGNADO) FROM ASIGNACIONES_DETALLE AD2 WHERE AD2.ASIGNACION_ID = AD.ASIGNACION_ID AND AD2.SUCURSAL_ID = S.SUCURSAL_ID) AS CODIGOS_ASIGNADOS, ";
        $select  .= " (SELECT COUNT(AD2.ESPACIO_ASIGNADO) FROM ASIGNACIONES_DETALLE AD2 WHERE AD2.ASIGNACION_ID = AD.ASIGNACION_ID AND AD2.SUCURSAL_ID = S.SUCURSAL_ID AND AD2.ESPACIO_ASIGNADO = 'S' AND AD2.EXHIBIDO = 'S') AS CODIGO_EXHIBIDO, ";
        $select  .= " (SELECT COUNT(AD2.ESPACIO_ASIGNADO) FROM ASIGNACIONES_DETALLE AD2 WHERE AD2.ASIGNACION_ID = AD.ASIGNACION_ID AND AD2.SUCURSAL_ID = S.SUCURSAL_ID AND AD2.EXHIBIDO = 'S' AND AD2.PRODUCTO_ETIQUETA = 'N') AS CODIGO_EXHIBIDO_ETIQUETA, ";
        $select  .= " (SELECT COUNT(AD2.ESPACIO_ASIGNADO) FROM ASIGNACIONES_DETALLE AD2 WHERE AD2.ASIGNACION_ID = AD.ASIGNACION_ID AND AD2.SUCURSAL_ID = S.SUCURSAL_ID AND AD2.SENALIZADO = 'N' AND AD2.ESPACIO_ASIGNADO = 'S') AS CODIGO_NO_SENALIZADO, ";
        $select  .= " (SELECT COUNT(AD2.ESPACIO_ASIGNADO) FROM ASIGNACIONES_DETALLE AD2 WHERE AD2.ASIGNACION_ID = AD.ASIGNACION_ID AND AD2.SUCURSAL_ID = S.SUCURSAL_ID AND AD2.EXHIBIDO = 'S' AND (AD2.PRECIO_ETIQUETA - AD2.PRECIO_VERIFICADOR) > 0) AS DIF_ETIQUETA_VERIFICADOR, ";
        $select  .= " (SELECT COUNT(AD2.ESPACIO_ASIGNADO) FROM ASIGNACIONES_DETALLE AD2 WHERE AD2.ASIGNACION_ID = AD.ASIGNACION_ID AND AD2.SUCURSAL_ID = S.SUCURSAL_ID AND AD2.EXHIBIDO = 'S' AND (AD2.PRECIO_ETIQUETA - AD2.PRECIO_VERIFICADOR) != 0) AS DIF_VERIFICADOR_ETIQUETA ";
        $select  .= " FROM ASIGNACIONES A ";
        $select  .= " JOIN ASIGNACIONES_DETALLE AD ON AD.ASIGNACION_ID = A.ASIGNACION_ID ";
        $select  .= " JOIN SUCURSALES S ON S.SUCURSAL_ID = AD.SUCURSAL_ID ";
        $select  .= " JOIN ARTICULOS AA ON AA.ARTICULO_ID = AD.ARTICULO_ID ";
        $select  .= " JOIN CATEGORIAS C ON C.CATEGORIA_ID = AA.CATEGORIA_ID ";
        $select .= " INNER JOIN DEPARTAMENTOS AS D ON C.DEPARTAMENTO_ID=D.DEPARTAMENTO_ID ";
        $select  .= " WHERE A.ASIGNACION_ID = (SELECT ASIGNACION_ID FROM ASIGNACIONES_DETALLE WHERE ASIGNACION_DETALLE_ID= $ASIGNACION_DETALLE_ID) AND S.SUCURSAL_ID = $SUCURSAL_ID AND A.ESTATUS = 'A' AND A.SISTEMA_ORIGEN = 'PROD_PRECI' ";
        $select  .= " GROUP BY C.CATEGORIA_ID) as RESUMEN ";
        $select  .= " group by FECHA_CAPTURA,CODIGOS_ASIGNADOS,CODIGO_EXHIBIDO,CODIGO_EXHIBIDO_ETIQUETA,CODIGO_NO_SENALIZADO,DIF_ETIQUETA_VERIFICADOR,DIF_VERIFICADOR_ETIQUETA;";
        $stmt = mysqli_query($conn, $select);
//ECHO $select;
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){  
                //,,,,,,,              
                $resumen["NOMBRE_CATEGORIAS"]           =$row["NOMBRE_CATEGORIAS"];
                $resumen["FECHA_CAPTURA"]               =$row["FECHA_CAPTURA"];
                $resumen["CODIGOS_ASIGNADOS"]           =$row["CODIGOS_ASIGNADOS"];
                $resumen["CODIGO_EXHIBIDO"]             =$row["CODIGO_EXHIBIDO"];
                $resumen["CODIGO_EXHIBIDO_ETIQUETA"]    =$row["CODIGO_EXHIBIDO_ETIQUETA"];
                $resumen["CODIGO_NO_SENALIZADO"]        =$row["CODIGO_NO_SENALIZADO"];
                $resumen["DIF_ETIQUETA_VERIFICADOR"]    =$row["DIF_ETIQUETA_VERIFICADOR"];
                $resumen["DIF_VERIFICADOR_ETIQUETA"]    =$row["DIF_VERIFICADOR_ETIQUETA"];
                $result[]=$resumen;
            } 
        }
        
        mysqli_close($conn);
            return $result;
    }
    else {
        return null; 
    }
}
$server->wsdl->addComplexType(
    'ResumenProductoPrecio',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'NOMBRE_CATEGORIAS' => array('name'=>'NOMBRE_CATEGORIAS','type'=>'xsd:string'),
        'FECHA_CAPTURA' => array('name'=>'FECHA_CAPTURA','type'=>'xsd:string'),
        'CODIGOS_ASIGNADOS' => array('name'=>'CODIGOS_ASIGNADOS','type'=>'xsd:string'),
        'CODIGO_EXHIBIDO' => array('name'=>'CODIGO_EXHIBIDO','type'=>'xsd:string'),
        'CODIGO_EXHIBIDO_ETIQUETA' => array('name'=>'CODIGO_EXHIBIDO_ETIQUETA','type'=>'xsd:string'),
        'CODIGO_NO_SENALIZADO' => array('name'=>'CODIGO_NO_SENALIZADO','type'=>'xsd:string'),
        'DIF_ETIQUETA_VERIFICADOR' => array('name'=>'DIF_ETIQUETA_VERIFICADOR','type'=>'xsd:string'),
        'DIF_VERIFICADOR_ETIQUETA' => array('name'=>'DIF_VERIFICADOR_ETIQUETA','type'=>'xsd:string')
        )
    );    
$server->wsdl->addComplexType(
    'ResumenProductoPrecioArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:ResumenProductoPrecio[]')),
    'tns:ResumenProductoPrecio'
    );
$server->register(
    'ResumenProductoPrecio',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'ASIGNACION_DETALLE_ID'=>'xsd:int'
    ),
    array('return'=> 'tns:ResumenProductoPrecioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los datos de resumen de producto y precio en sucursal determinada');

function ResumenArticulosCaducados($FECHA_REVISION,$SUCURSAL_ID,$CADUCIDAD_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        $select  = " SELECT ";
        $select .= " AR.SKU AS SKU, ";
        $select .= " AR.DESCRIPCION AS ARTICULO, ";
        $select .= " IFNULL(CD.EXISTENCIAS, 'N') AS EXISTENCIAS, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.ARTICULO_LOTE FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 1 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS ARTICULO_LOTE_1, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.FECHA_CADUCIDAD FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 1 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS FECHA_CADUCIDAD_1, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.CANTIDAD FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 1 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), 0) AS CANTIDAD_1, ";
        $select .= " IFNULL((SELECT DISTINCT DATEDIFF( CDF.FECHA_CADUCIDAD,'$FECHA_REVISION' )  FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 1 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS CADUCADO_1, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.UBICACION FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 1 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS UBICACION_1, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.ARTICULO_LOTE FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 2 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS ARTICULO_LOTE_2, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.FECHA_CADUCIDAD FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 2 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS FECHA_CADUCIDAD_2, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.CANTIDAD FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 2 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), 0) AS CANTIDAD_2, ";
        $select .= " IFNULL((SELECT DISTINCT DATEDIFF( CDF.FECHA_CADUCIDAD,'$FECHA_REVISION' )  FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 2 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS CADUCADO_2, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.UBICACION FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 2 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS UBICACION_2, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.ARTICULO_LOTE FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 3 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS ARTICULO_LOTE_3, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.FECHA_CADUCIDAD FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 3 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS FECHA_CADUCIDAD_3, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.CANTIDAD FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 3 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), 0) AS CANTIDAD_3, ";
        $select .= " IFNULL((SELECT DISTINCT DATEDIFF( CDF.FECHA_CADUCIDAD,'$FECHA_REVISION' )  FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 3 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS CADUCADO_3, ";
        $select .= " IFNULL((SELECT DISTINCT CDF.UBICACION FROM CADUCIDADES_DETALLE_FECHAS AS CDF ";
        $select .= " WHERE CDF.CADUCIDADES_DETALLE_ID = CD.CADUCIDADES_DETALLE_ID ";
        $select .= " AND CDF.POSICION = 3 ORDER BY CDF.CADUCIDADES_DETALLE_FECHAS_ID DESC LIMIT 1), '') AS UBICACION_3 ";
        $select .= " FROM CADUCIDADES AS CE ";
        $select .= " JOIN CLIENTES AS CL ON(CE.CLIENTE_ID = CL.CLIENTE_ID) ";
        $select .= " JOIN CADUCIDADES_DETALLE AS CD ON (CE.CADUCIDAD_ID = CD.CADUCIDAD_ID) ";
        $select .= " LEFT JOIN CADUCIDADES_DETALLE_SUCURSALES AS CDS ON(CD.CADUCIDAD_ID = CDS.CADUCIDADES_ID AND CD.SUCURSAL_ID = CDS.SUCURSAL_ID) ";
        $select .= " JOIN SUCURSALES AS SU ON(CD.SUCURSAL_ID = SU.SUCURSAL_ID) ";
        $select .= " JOIN ARTICULOS AS AR ON(CD.ARTICULO_ID = AR.ARTICULO_ID) ";
        $select .= " JOIN CATEGORIAS AS CA ON(AR.CATEGORIA_ID = CA.CATEGORIA_ID) ";
        $select .= " JOIN PROVEEDORES AS PV ON (AR.PROVEEDOR_ID = PV.PROVEEDOR_ID) ";
        $select .= " WHERE CE.FECHA_REVISION = '$FECHA_REVISION' ";
        $select .= " AND CD.ESPACIO_ASIGNADO IS NOT NULL ";
        $select .= " AND SU.SUCURSAL_ID=$SUCURSAL_ID ";
        $select .= " AND CE.CADUCIDAD_ID=$CADUCIDAD_ID";
        $stmt = mysqli_query($conn, $select); 
        //echo $select;
        while ($row = mysqli_fetch_assoc($stmt)){
            
            $resumen["SKU"]                 =$row["SKU"];
            $resumen["ARTICULO"]            =$row["ARTICULO"];
            $resumen["EXISTENCIAS"]         =$row["EXISTENCIAS"];
            $resumen["ARTICULO_LOTE_1"]     =$row["ARTICULO_LOTE_1"];
            $resumen["FECHA_CADUCIDAD_1"]   =$row["FECHA_CADUCIDAD_1"];
            $resumen["CANTIDAD_1"]          =$row["CANTIDAD_1"];
            $resumen["CADUCADO_1"]          =$row["CADUCADO_1"];
            $resumen["UBICACION_1"]         =$row["UBICACION_1"];
            $resumen["ARTICULO_LOTE_2"]     =$row["ARTICULO_LOTE_2"];
            $resumen["FECHA_CADUCIDAD_2"]   =$row["FECHA_CADUCIDAD_2"];
            $resumen["CANTIDAD_2"]          =$row["CANTIDAD_2"];
            $resumen["CADUCADO_2"]          =$row["CADUCADO_2"];
            $resumen["UBICACION_2"]         =$row["UBICACION_2"];
            $resumen["ARTICULO_LOTE_3"]     =$row["ARTICULO_LOTE_3"];
            $resumen["FECHA_CADUCIDAD_3"]   =$row["FECHA_CADUCIDAD_3"];
            $resumen["CANTIDAD_3"]          =$row["CANTIDAD_3"];
            $resumen["CADUCADO_3"]          =$row["CADUCADO_3"];
            $resumen["UBICACION_3"]         =$row["UBICACION_3"];
            $result[]=$resumen;
        } 

        if ($stmt){
            mysqli_close($conn);
        }
        else {
            return null; 
        }
    }
    return $result;
}
$server->wsdl->addComplexType(
    'ResumenArticulosCaducados',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'SKU' => array('name'=>'SKU','type'=>'xsd:string'),
        'ARTICULO' => array('name'=>'ARTICULO','type'=>'xsd:string'),        
        'EXISTENCIAS' => array('name'=>'EXISTENCIAS','type'=>'xsd:string'),
        'ARTICULO_LOTE_1' => array('name'=>'ARTICULO_LOTE_1','type'=>'xsd:string'),
        'FECHA_CADUCIDAD_1' => array('name'=>'FECHA_CADUCIDAD_1','type'=>'xsd:string'),
        'CANTIDAD_1' => array('name'=>'CANTIDAD_1','type'=>'xsd:string'),
        'CADUCADO_1' => array('name'=>'CADUCADO_1','type'=>'xsd:string'),
        'UBICACION_1' => array('name'=>'UBICACION_1','type'=>'xsd:string'),
        'ARTICULO_LOTE_2' => array('name'=>'ARTICULO_LOTE_2','type'=>'xsd:string'),
        'FECHA_CADUCIDAD_2' => array('name'=>'FECHA_CADUCIDAD_2','type'=>'xsd:string'),
        'CANTIDAD_2' => array('name'=>'CANTIDAD_2','type'=>'xsd:string'),
        'CADUCADO_2' => array('name'=>'CADUCADO_2','type'=>'xsd:string'),
        'UBICACION_2' => array('name'=>'UBICACION_2','type'=>'xsd:string'),
        'ARTICULO_LOTE_3' => array('name'=>'ARTICULO_LOTE_3','type'=>'xsd:string'),
        'FECHA_CADUCIDAD_3' => array('name'=>'FECHA_CADUCIDAD_3','type'=>'xsd:string'),
        'CANTIDAD_3' => array('name'=>'CANTIDAD_3','type'=>'xsd:string'),
        'CADUCADO_3' => array('name'=>'CADUCADO_3','type'=>'xsd:string'),
        'UBICACION_3' => array('name'=>'UBICACION_3','type'=>'xsd:string')        
        )
    );    
$server->wsdl->addComplexType(
    'ResumenArticulosCaducadosArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:ResumenArticulosCaducados[]')),
    'tns:ResumenArticulosCaducados'
    );
$server->register(
    'ResumenArticulosCaducados',
    array(
        'FECHA_REVISION'=>'xsd:string',
        'SUCURSAL_ID'=>'xsd:int',
        'CADUCIDAD_ID'=>'xsd:int'
    ),
    array('return'=> 'tns:ResumenArticulosCaducadosArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos caducados en una fecha determinada');
