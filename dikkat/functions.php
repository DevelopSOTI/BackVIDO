<?php
    date_default_timezone_set("America/Monterrey");
    error_reporting(E_ALL ^ E_DEPRECATED);
    set_time_limit(0);

    function conectaDb($index)
    {
        $file = $index === FALSE ? "../../data/conexion_mysql.csv" : "./data/conexion_mysql.csv";

        if (file_exists($file))
        {
            $fp = fopen($file, "r");
            if ($fp != FALSE)
            {
                while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
                {
                    $i = 1;
                    
                    foreach ($data as $row)
                    {
                        switch ($i)
                        {
                            case 1: $host = $row; break; // HOST DONDE ESTA ALOJADA LA BASE DE DATOS
                            case 2: $user = $row; break; // NOMBRE DEL USUARIO
                            case 3: $pass = $row; break; // CONTRASEÑA
                            case 4: $base = $row; break; // NOMBRE DE LA BASE DE DATOS
                            // case 5: $port = $row; break; // PUERTO DE CONEXIÓN
                        }

                        $i++;
                    }
                }
                fclose ( $fp );
                
                try {
                    $tmp = new PDO($host, $user, $pass,$base);
                    $tmp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                    $tmp->exec("set names utf8mb4");
                    return $tmp;
                } catch (PDOException $e) {
                    print "    <p class=\"aviso\">Error: No puede conectarse con la base de datos.</p>\n";
                    print "\n";
                    print "    <p class=\"aviso\">Error: " . $e->getMessage() . "</p>\n";
                    exit();
                }
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }
    function ABRIR_CONEXION_MYSQL($index, $database)
    {
        $file = $index === FALSE ? "../../data/conexion_mysql.csv" : "./data/conexion_mysql.csv";

        if (file_exists($file))
        {
            $fp = fopen($file, "r");
            if ($fp != FALSE)
            {
                while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
                {
                    $i = 1;
                    
                    foreach ($data as $row)
                    {
                        switch ($i)
                        {
                            case 1: $host = $row; break; // HOST DONDE ESTA ALOJADA LA BASE DE DATOS
                            case 2: $user = $row; break; // NOMBRE DEL USUARIO
                            case 3: $pass = $row; break; // CONTRASEÑA
                            case 4: $base = $row; break; // NOMBRE DE LA BASE DE DATOS
                            // case 5: $port = $row; break; // PUERTO DE CONEXIÓN
                        }

                        $i++;
                    }
                }
                fclose ( $fp );
                
                $conexion = @mysqli_connect($host, $user, $pass);
                $conexion->set_charset('utf8');
                if ($conexion != FALSE)
                {
                    // INDICAMOS LA BASE DE DATOS A LA CUAL CONECTARSE
                    mysqli_select_db($conexion, $database);
                    return $conexion;
                }
                else
                {
                    echo "Falso";
                    return FALSE;
                }
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    function ABRIR_CONEXION_MYSQLI($index,$database)
    {
        $file = $index === FALSE ? "../../data/conexion_mysql.csv" : "./data/conexion_mysql.csv";

        if (file_exists($file))
        {
            $fp = fopen($file, "r");
            if ($fp != FALSE)
            {
                while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
                {
                    $i = 1;
                    
                    foreach ($data as $row)
                    {
                        switch ($i)
                        {
                            case 1: $host = $row; break; // HOST DONDE ESTA ALOJADA LA BASE DE DATOS
                            case 2: $user = $row; break; // NOMBRE DEL USUARIO
                            case 3: $pass = $row; break; // CONTRASEÑA
                            case 4: $base = $row; break; // NOMBRE DE LA BASE DE DATOS
                            // case 5: $port = $row; break; // PUERTO DE CONEXIÓN
                        }

                        $i++;
                    }
                }
                fclose ( $fp );
                
                $conexion = new mysqli($host, $user, $pass);
                //$conexion->set_charset('utf8');
                if ($conexion != FALSE)
                {
                    // INDICAMOS LA BASE DE DATOS A LA CUAL CONECTARSE
                    mysqli_select_db($conexion, $database);
                    return $conexion;
                }
                else
                {
                    echo "Falso";
                    return FALSE;
                }
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key)
        {
            if (!mb_detect_encoding($item, 'utf-8', true))
            {
                $item = utf8_encode($item);
            }
        });

        return $array;
    }
