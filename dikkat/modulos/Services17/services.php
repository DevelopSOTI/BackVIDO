<?php
    require_once '../../functions.php';
    require_once './nusoap-master/src/nusoap.php';
    //error_reporting(0);
    mb_internal_encoding('UTF-8');
    define("DB_MASTER","soticomm_VIDO_MASTER");
    //define("DB_KLYNS","soticomm_VIDO_KLYNS");
    define("HOSTNAME","../../../../");
    
    // <editor-fold defaultstate="collapsed" desc="ValidarConexion">

    function ValidarConexion()
    {
        $conn = ABRIR_CONEXION_MYSQL(FALSE,DB_MASTER);

        if ($conn)
        {
            mysqli_close($conn);

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function getClaveMaestraCliente($BD){
        $CLAVE_CLIENTE_MASTER = "";
    $conn = ABRIR_CONEXION_MYSQL(FALSE, DB_MASTER);
    $select_Clave = "select CLAVE FROM soticomm_VIDO_MASTER.CLIENTES WHERE NOMBRE_DB = '$BD'";
    $stmt = mysqli_query($conn, $select_Clave);
    if($stmt){
        while($row = mysqli_fetch_assoc($stmt)){
            $CLAVE_CLIENTE_MASTER = $row["CLAVE"];
        }
    }
    mysqli_close($conn);
    return $CLAVE_CLIENTE_MASTER;
    }

   // </editor-fold>

    $namespace = "http://webproviders.mx/soap/Services";

    $server = new soap_server();
    $server->configureWSDL("Services");
    $server->configureWSDL("Services", $namespace);
    $server->wsdl->schemaTargetNamespace = $namespace;
    $server->soap_defencoding = 'UTF-8';
    $server->decode_utf8 = false;
    $server->encode_utf8 = true;

    $server->register(
        'ValidarConexion',
        array(),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Funcion que valida si hace la conexi��n al sistema de InMex.');

        $server->register(
            'getClaveMaestraCliente',
            array('DB'=>'xsd:string'),
            array('return'=>'xsd:string'),
            $namespace,
            false,
            'rpc',
            false,
            'Funcion que devuelve la clave de una base de datos');
    


        //MAURO------------------------------------------------------------
    include './usuarios.php';
    include './login.php'; //esta no modificar
    include './sucursales.php';
    include './direcciones_sucursales.php';
    //include './clientes.php';
    //include './roles.php'; 
    //include './departamentos.php';

    include './categorias.php';
    include './sucursales_usuarios.php';
    include './asistencia.php';
    include './ausencias.php';
    // include './login2.php';
    include './programacion_visita_det.php';
    include './promotores.php';
    include './sucursales_proveedores_dias.php';
          //MAURO------------------------------------------------------------

          //DARIO-------------------------------------------------------------
    include './producto_precio.php';
    include './faltantes_shopper.php';
    include './caducidades.php';
    include './chequeo_competencias.php';
    include './mystery_shopper.php';
    include './faltantes_shopper_2.php';
    include './resumenes.php';
    include './barrido.php';
    include './dominios.php';
    include './checar_asistencia.php';
              //DARIO-------------------------------------------------------------
    include './master.php';
    
    $post = file_get_contents('php://input');
    $server->service($post);
    
    exit();