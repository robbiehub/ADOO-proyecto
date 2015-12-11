<?php

    function dame_proceso($nombre) {
        include("conexion.php");
        $sql = "select * from t2";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $lista = array();
            while ($row = $result->fetch_assoc()) {
                array_push($lista, json_decode($row["proceso"], true));
            }

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $proceso = NULL;
        foreach($lista as $key => $value) {
            if ($value['proceso'] == $nombre) {
                $proceso = $lista[$key];
            }
        }
        return $proceso;
    }

    function dame_proceso_json($nombre) {

        $proceso = dame_proceso($nombre);
        if ($proceso) {
            if ($proceso['formato'] === "iso_12207"){
                $proceso_json = array();


                $proceso_json += array("proceso" => $proceso["proceso"]);
                $proceso_json += array("id" => $proceso["p_id"]);
                $proceso_json += array("notas_proceso" => array());
                $proceso_json += array("proposito" => $proceso["proposito_0"]);
                $proceso_json += array("resultados" => array());
                $proceso_json += array("actividades" => array());

                foreach ($proceso as $key => $value) {
                    if ($key[0] === "a") {
                        $proceso_json["actividades"][$key] = array();
                        array_push($proceso_json["actividades"][$key], $value);
                        // $proceso_json["actividades"][$key]["tareas"] = array();
                    }
                    if ($key[0] === "t") {
                        $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key] = array();
                        array_push($proceso_json["actividades"][endKey($proceso_json["actividades"])][$key], $value);
                        $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key]["notas"] = array();
                        $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key]["opciones"] = array();
                    }
                    if (strpos($key, "nota_tarea_") !== false) {
                        $f = endKey($proceso_json["actividades"]);
                        $f2 = endKey($proceso_json["actividades"][$f]);
                        array_push($proceso_json["actividades"][$f][$f2]["notas"], $value);
                    }
                    if (strpos($key, "nota_num") !== false) {
                        array_push($proceso_json["notas_proceso"], $value);
                    }
                    if (strpos($key, "resultado") !== false) {
                        array_push($proceso_json["resultados"], $value);
                    }
                    if (strpos($key, "opcion") !== false) {
                        $f = endKey($proceso_json["actividades"]);
                        $f2 = endKey($proceso_json["actividades"][$f]);
                        array_push($proceso_json["actividades"][$f][$f2]["opciones"], $value);
                    }

                }

            }else

            if ($proceso['formato'] === "iso_29110"){
              $proceso_json = array();
              $proceso_json += array("proceso" => $proceso["proceso"]);
              $proceso_json += array("id" => $proceso["p_id"]);
              $proceso_json += array("proposito" => $proceso["p_proposito"]);
              $proceso_json += array("objetivos" => array());
              $proceso_json += array("roles" => array());
              $proceso_json += array("productos_entrada" => array());
              $proceso_json += array("productos_salida" => array());
              $proceso_json += array("productos_interno" => array());
              $proceso_json += array("actividades" => array());

              foreach ($proceso as $key => $value) {
                  if(strpos($key, "obj") !== false){
                    array_push($proceso_json["objetivos"], $value);
                  }

                  if(strpos($key, "rol_") !== false && strpos($key, "_abr_") === false && strpos($key, "_rol_") === false){
                    //$proceso_json["roles"][$key]
                    // if(strpos($key, "_abr_") === false && strpos($key, "_rol_") === false ){
                    $proceso_json["roles"][$key] = array();
                    array_push($proceso_json["roles"][$key], $value);
                    // }
                    // array_push($proceso_json["roles"], $value);
                  }
                  if(strpos($key, "rol_abr_") !== false){
                    $f = endKey($proceso_json["roles"]);
                    array_push($proceso_json["roles"][$f], $value);
                  }

                  if(strpos($key, "pent_nombre_") !== false){
                    $proceso_json["productos_entrada"][$key] = array();
                    array_push($proceso_json["productos_entrada"][$key], $value);
                  }
                  if(strpos($key, "pent_fuente_") !== false){
                    $f = endKey($proceso_json["productos_entrada"]);
                    array_push($proceso_json["productos_entrada"][$f], $value);
                  }

                  if(strpos($key, "psal_nombre_") !== false){
                    $proceso_json["productos_salida"][$key] = array();
                    array_push($proceso_json["productos_salida"][$key], $value);
                  }
                  if(strpos($key, "psal_destino_") !== false){
                    $f = endKey($proceso_json["productos_salida"]);
                    array_push($proceso_json["productos_salida"][$f], $value);
                  }

                  if(strpos($key, "pint_") !== false){
                    array_push($proceso_json["productos_interno"], $value);
                  }

                  if ($key[0] === "a") {
                      $proceso_json["actividades"][$key] = array();
                      array_push($proceso_json["actividades"][$key], $value);
                      // $proceso_json["actividades"][$key]["tareas"] = array();
                  }
                  if (strpos($key, "tar_nombre_") !== false) {
                      // $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key] = array();
                      // array_push($proceso_json["actividades"][endKey($proceso_json["actividades"])][$key], $value);
                      $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key]= array();
                      // $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key]["tar_roles"] = array();
                      // $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key]["tar_pent"] = array();
                      // $proceso_json["actividades"][endKey($proceso_json["actividades"])][$key]["tar_psal"] = array();
                      array_push($proceso_json["actividades"][endKey($proceso_json["actividades"])][$key], $value);
                  }
                  if(strpos($key, "tar_rol_") !== false){
                    $f = endKey($proceso_json["actividades"]);
                    $f2 = endKey($proceso_json["actividades"][$f]);
                    array_push($proceso_json["actividades"][$f][$f2], $value);
                  }
                  if(strpos($key, "tar_pent_") !== false){
                    $f = endKey($proceso_json["actividades"]);
                    $f2 = endKey($proceso_json["actividades"][$f]);
                    array_push($proceso_json["actividades"][$f][$f2], $value);
                  }
                  if(strpos($key, "tar_psal_") !== false){
                    $f = endKey($proceso_json["actividades"]);
                    $f2 = endKey($proceso_json["actividades"][$f]);
                    array_push($proceso_json["actividades"][$f][$f2], $value);
                  }
                }
            }

            if ($proceso["formato"] === "libro") {
              $proceso_json = array();
              $proceso_json+= array("proceso" => $proceso["proceso"]);
              $proceso_json+= array("id" => $proceso["p_id"]);
              $proceso_json+= array("objetivo_general" => $proceso["p_objetivo"]);
              $proceso_json+= array("objetivos_especificios" => array());
              $proceso_json+= array("actividades" => array());
              $proceso_json+= array("roles" => array());
              $proceso_json+= array("productos" => array());
              foreach($proceso as $key => $value) {
                  if (strpos($key, "obj_") !== false) {
                      array_push($proceso_json["objetivos_especificios"], $value);
                  }
                  if (strpos($key, "act_") !== false) {
                      array_push($proceso_json["actividades"], array());
                      $proceso_json["actividades"][endKey($proceso_json["actividades"])] += array("descripcion" => $value);
                      $proceso_json["actividades"][endKey($proceso_json["actividades"])] += array("metodos" => array());
                  }
                  if (strpos($key, "met") !== false) {
                      array_push($proceso_json["actividades"][endKey($proceso_json["actividades"])]["metodos"], $value);
                  }
                  if (strpos($key, "rol_desc") !== false) {
                      array_push($proceso_json["roles"], array());
                      $proceso_json["roles"][endKey($proceso_json["roles"])] += array("rol" => $value);
                  }
                  if (strpos($key, "rol_nombre") !== false) {
                      $proceso_json["roles"][endKey($proceso_json["roles"])] += array("rol_nombre" => $value);
                  }
                  if (strpos($key, "prod") !== false) {
                      array_push($proceso_json["productos"], $value);
                  }
                }
                $proceso_json["objetivos_especificios"] = array_reverse($proceso_json["objetivos_especificios"]);
                $proceso_json["actividades"] = array_reverse($proceso_json["actividades"]);
                $proceso_json["roles"] = array_reverse($proceso_json["roles"]);
                $proceso_json["productos"] = array_reverse($proceso_json["productos"]);
              }


        }else{
          echo "Error al seleccionar la fila del proceso en la base de datos";
        }
          return $proceso_json;


      }



    function endKey($array){
        end($array);
        $end_key = key($array);
        reset($array);
        return $end_key;
    }


?>
