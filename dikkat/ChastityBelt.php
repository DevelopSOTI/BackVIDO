<?php
class ChastityBelt {
    public $cadena1, $cadena2, $numero1, $numero2,$mensaje;
    public $palabraReservada;
    public function __construct() {
        $this->cadena1="";
        $this->cadena2="";
        $this->numero1=0;
        $this->numero2=0;
        $this->mensaje="";
        $this->palabraReservada=array(" ADD "," ALL "," ALTER "," ANALYZE "," AND "," AS "," ASC "," ASENSITIVE "," BEFORE "," BETWEEN "," BIGINT "," BINARY "," BLOB "," BOTH "," BY "," CALL "," CASCADE "," CASE "," CHANGE "," CHAR "," CHARACTER "," CHECK "," COLLATE "," COLUMN "," CONDITION "," CONNECTION "," CONSTRAINT "," CONTINUE "," CONVERT "," CREATE "," CROSS "," CURRENT_DATE "," CURRENT_TIME "," CURRENT_TIMESTAMP "," CURRENT_USER "," CURSOR "," DATABASE "," DATABASES "," DAY_HOUR "," DAY_MICROSECOND "," DAY_MINUTE "," DAY_SECOND "," DEC "," DECIMAL "," DECLARE "," DEFAULT "," DELAYED "," DELETE "," DESC "," DESCRIBE "," DETERMINISTIC "," DISTINCT "," DISTINCTROW "," DIV "," DOUBLE "," DROP "," DUAL "," EACH "," ELSE "," ELSEIF "," ENCLOSED "," ESCAPED "," EXISTS "," EXIT "," EXPLAIN "," FALSE "," FETCH "," FLOAT "," FOR "," FORCE "," FOREIGN "," FROM "," FULLTEXT "," GOTO "," GRANT "," GROUP "," HAVING "," HIGH_PRIORITY "," HOUR_MICROSECOND "," HOUR_MINUTE "," HOUR_SECOND "," IF "," IGNORE "," IN "," INDEX "," INFILE "," INNER "," INOUT "," INSENSITIVE "," INSERT "," INT "," INTEGER "," INTERVAL "," INTO "," IS "," ITERATE "," JOIN "," KEY "," KEYS "," KILL "," LEADING "," LEAVE "," LEFT "," LIKE "," LIMIT "," LINES "," LOAD "," LOCALTIME "," LOCALTIMESTAMP "," LOCK "," LONG "," LONGBLOB "," LONGTEXT "," LOOP "," LOW_PRIORITY "," MATCH "," MEDIUMBLOB "," MEDIUMINT "," MEDIUMTEXT "," MIDDLEINT "," MINUTE_MICROSECOND "," MINUTE_SECOND "," MOD "," MODIFIES "," NATURAL "," NO_WRITE_TO_BINLOG "," NOT "," NULL "," NUMERIC "," ON "," OPTIMIZE "," OPTION "," OPTIONALLY "," OR "," ORDER "," OUT "," OUTER "," OUTFILE "," PRECISION "," PRIMARY "," PROCEDURE "," PURGE "," READ "," READS "," REAL "," REFERENCES "," REGEXP "," RENAME "," REPEAT "," REPLACE "," REQUIRE "," RESTRICT "," RETURN "," REVOKE "," RIGHT "," RLIKE "," SCHEMA "," SCHEMAS "," SECOND_MICROSECOND "," SELECT "," SENSITIVE "," SEPARATOR "," SET "," SHOW "," SMALLINT "," SONAME "," SPATIA "," SPECIFIC "," SQL "," SQL_BIG_RESULT "," SQL_CALC_FOUND_ROWS "," SQL_SMALL_RESULT "," SQLEXCEPTION "," SQLSTATE "," SQLWARNING "," SSL "," STARTING "," STRAIGHT_JOIN "," TABLE "," TERMINATED "," THEN "," TINYBLOB "," TINYINT "," TINYTEXT "," TO "," TRAILING "," TRIGGER "," TRUE "," UNDO "," UNION "," UNIQUE "," UNLOCK "," UNSIGNED "," UPDATE "," USAGE "," USE "," USING "," UTC_DATE "," UTC_TIME "," UTC_TIMESTAMP "," VALUES "," VARBINARY "," VARCHAR "," VARCHARACTER "," VARYING "," WHEN "," WHERE "," WHILE "," WITH "," WRITE "," XOR "," YEAR_MONTH "," ZEROFILL ");
        
    }
    public function errores ($Error)
    {        
        $this->mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong> Error!</strong> '.$Error.'. </div>';;
        echo $this->mensaje;
    }

    public function ValidaCadena($cadenaEntrada,$longitudCadena,$longMin)
    {
        $bandera=false;
        if (/*(strlen($cadenaEntrada)>=$longMin)&&(strlen($cadenaEntrada)<=$longitudCadena)&&*/ (strlen($cadenaEntrada)>0))
        {
            if($this->ValidaPalabraReservadas($cadenaEntrada))
            {
                if($this->ValidaCaracteresNoPermitidos($cadenaEntrada))
                {
                    $bandera=true;
                }
                else 
                {
                    $bandera=false;
                    $this->errores("Contiene caracteres no válidos\n Cadena de entrada: \"".$cadenaEntrada."\"");
                }
            }
            else
            {
                $bandera=false;
                $this->errores("Contiene palabra reservada\n Cadena de entrada: \"".$cadenaEntrada."\"");
            }
        }
        else
        {
            $bandera=false;
            $this->errores("La longitud de la cadena no permitida\n Cadena de entrada: \"".$cadenaEntrada."\"");
        }
        return $bandera;
    }
    public function ValidaPalabraReservadas($cadena)
    {
        $bandera=false;
          foreach ($this->palabraReservada as &$valor) 
              {
                  if (strlen(stristr($cadena,$valor))>0)
                  {
                        $bandera=false;
                        break;
                  }
                  else
                  {
                      $bandera=true;
                  }
              }      
        return $bandera;
    }
    public function ValidaCaracteresNoPermitidos($cadena)
    {
        $bandera=false;
                $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_@. ñÑ'";
        for ($i=0; $i<strlen($cadena); $i++)
        {
           if (strpos($permitidos, substr($cadena,$i,1))===false)
                   {              
               $bandera= false;
               break;
               }
               else
                   {
                   $bandera=true;
                   }
        }         
        return $bandera;
    }
}
