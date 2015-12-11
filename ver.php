<?php
    include("base.php");
    include("jsonificador.php");

    $proceso = dame_proceso($_GET["nombre"]);
    $proceso_json = dame_proceso_json($_GET["nombre"]);

    echo "<body><div class=\"container\">";
    if ($proceso["formato"] === "iso_12207") {

        echo "
            <div class=\"page-header\">
                <h3>
                    ".$proceso_json["id"]." ".$proceso_json["proceso"]."
                </h3>
            </div>";

        echo "<div class=\"list-group\">
                <li class=\"list-group-item\">
                    <h4>Notas</h4>";
        foreach($proceso_json["notas_proceso"] as $key => $notas) {
            echo "<h5>".$notas."</h5>";
        }
        echo "</li>";

        echo "<div class=\"list-group\">
                <li class=\"list-group-item\">
                    <h4>".$proceso_json["id"].".1 "."Proposito</h4>
                    <h5>".$proceso_json["proposito"]."</h5>
                </li>
            ";
        echo "  <li class=\"list-group-item\">
                    <h4>".$proceso_json["id"].".2 "."Resultados</h4>";

        foreach($proceso_json["resultados"] as $key => $resultado) {
            echo "<h5>".chr(65 + $key).") ".$resultado."</h5>";
        }
        echo "</li>";
        echo "<li class=\"list-group-item\">
                <h4>".$proceso_json["id"].".3 "."Actividades</h4>";
                $cont_act=0;
        foreach ($proceso_json["actividades"] as $key_act => $value_act) {
            // key_act es cada actividad (act_0)
            // print_r($value_act[1]);
            $cont = 1;
            $cont_act++;
            foreach ($value_act as $key => $value) {
                // $value act es el array de la actividad
                // key es la key de lo que tiene cada actividad
                if ($key === 0) {
                    echo "<h5><b>".$proceso_json["id"].".3.".$cont_act." ".$value_act[0]."</b></h5>";

                } else {
                    foreach($value as $key_tarea => $value_tarea) {
                        if ($key_tarea === 0) {
                            echo "<b>".$proceso_json["id"].".3.".$cont_act.".".$cont."</b> ";
                            echo $value_tarea."<br>";
                        } else {
                            foreach($value_tarea as $key_nota_opcion => $value_nota_opcion) {
                                if ($key_tarea === "notas") {
                                    echo "<i>Notas</i><br>";
                                    echo "<p>".$value_nota_opcion."</p>";
                                } else {
                                    echo "Opciones<br>";
                                    echo "<p>".$value_nota_opcion."</p>";
                                }
                            }
                        }
                    }
                    $cont++;

                }

                // echo "aquiiii ".$value;
                // echo "<h5>".$proceso_json["id"].".3.".key($proceso_json["actividades"][$key_act]).".".key(proceso_json["actividades"])."</h5>";
            }
        }
        echo "</li>";
        echo "</div>";

    }

    if ($proceso["formato"] === "iso_29110"){
      //Proceso y id
      echo "
          <div class=\"page-header\">
              <h3>
                  ".$proceso_json["id"]." ".$proceso_json["proceso"]."
              </h3>
          </div></li>";

      echo "<div class=\"list-group\">
              <li class=\"list-group-item\">
                  <h4>".$proceso_json["id"].".1 "."Proposito</h4>
                  <h5>".$proceso_json["proposito"]."</h5>
              </li>";
      //Aqui se imprimen los objetivos
      echo "  <li class=\"list-group-item\">
                  <h4>".$proceso_json["id"].".2 "."Objetivos</h4>";
      $proceso_json["objetivos"] = array_reverse($proceso_json["objetivos"]);
      foreach($proceso_json["objetivos"] as $key => $objetivo) {
          echo "<h5>".chr(65 + $key).") ".$objetivo."</h5>";
      }
      echo "</li>";
      //Aqui se imprimen los roles.
      echo "<li class=\"list-group-item\">
            <h4>".$proceso_json["id"].".3 Rol</h4>";
            if(!empty($proceso_json["roles"])){
      echo    "<table class=\"table table-striped\">
                <thead>
                  <tr>
                    <th>Rol</th>
                    <th>Abreviación</th>
                  </tr>
                </thead>
                <tbody>";
                $proceso_json["roles"] = array_reverse($proceso_json["roles"]);
                foreach($proceso_json["roles"] as $key_rol => $value_rol){
                  echo "<tr>
                          <td>".$value_rol[0]."</td>
                          <td>".$value_rol[1]."</td>
                        </tr>";
                }
            echo "</tbody>
                  </table>
                  </li>";
            }else {
              echo "No hay roles";
            }

      //Aqui se imprimen los productos de entrada.
      echo "<li class=\"list-group-item\">
            <h4>".$proceso_json["id"].".4 Productos de entrada</h4>";
            if(!empty($proceso_json["productos_entrada"])){
              echo "<table class=\"table table-striped\">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Fuente</th>
                  </tr>
                </thead>
                <tbody>";
                $proceso_json["productos_entrada"] = array_reverse($proceso_json["productos_entrada"]);
                foreach($proceso_json["productos_entrada"] as $key_pent => $value_pent){
                  echo "<tr>
                          <td>".$value_pent[0]."</td>
                          <td>".$value_pent[1]."</td>
                        </tr>";
                }
                echo "</tbody>
                      </table>
                      </li>";
              }else {
                echo "No hay productos de entrada.";
              }

      //Aqui se imprimen los productos de salida.
      echo "<li class=\"list-group-item\">
            <h4>".$proceso_json["id"].".5 Productos de salida</h4>";
            if(!empty($proceso_json["productos_salida"])){
            echo "<table class=\"table table-striped\">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Destino</th>
                </tr>
              </thead>
              <tbody>";
              $proceso_json["productos_salida"] = array_reverse($proceso_json["productos_salida"]);
              foreach($proceso_json["productos_salida"] as $key_psal => $value_psal){
                echo "<tr>
                        <td>".$value_psal[0]."</td>
                        <td>".$value_psal[1]."</td>
                      </tr>";
              }
              echo "</tbody>
                    </table>
                    </li>";
            }else {
              echo "No hay productos de salida";
            }

      //Aqui se imprimen los productos internos.
      echo "<li class=\"list-group-item\">
            <h4>".$proceso_json["id"].".6 Productos internos</h4>";
            if(!empty($proceso_json["productos_interno"])){
              echo "<table class=\"table table-striped\">
                <thead>
                  <tr>
                    <th>Nombre</th>
                  </tr>
                </thead>
                <tbody>";
                $proceso_json["productos_interno"] = array_reverse($proceso_json["productos_interno"]);
                foreach($proceso_json["productos_interno"] as $key_pint => $value_pint){
                  echo "<tr>
                          <td>".$value_pint."</td>
                        </tr>";
                }
                echo "</tbody>
                      </table>
                      </li>";
              }else {
                echo "No hay productos internos.";
              }

      //Aqui se imprimen las actividades y tareas
      echo "<li class=\"list-group-item\">
              <h4>".$proceso_json["id"].".7 "."Actividades</h4>";
      if(!empty($proceso_json["actividades"])){
        $cont = 1;
        foreach ($proceso_json["actividades"] as $key_act => $value_act) {
            // key_act es cada actividad (act_0)
            // print_r($value_act[1]);
            foreach ($value_act as $key => $value) {
                // $value act es el array de la actividad
                // key es la key de lo que tiene cada actividad
                //print_r($value);
                if ($key === 0) {
                    echo "<h5>".$proceso_json["id"].".7.".$cont." ".$value_act[0]."</h5>";
                    if(count($value_act)>1){
                      echo "<table class=\"table table-striped\">
                              <thead>
                                <tr>
                                  <th>Nombre de la tarea</th>
                                  <th>Rol</th>
                                  <th>Producto de entrada</th>
                                  <th>Producto de salida</th>
                                </tr>
                              </thead>
                              <tbody>";
                      }
                } else {
                    echo "<tr>";
                    foreach($value as $key_tarea => $value_tarea) {
                      echo "<td>".$value_tarea."</td>";
                    }
                    echo "</tr>";
                }

            }
            echo "</tbody>
                  </table>";
            $cont++;
        }
      }else {
        echo "No hay actividades.";
      }
      echo "</li>";
      echo "</div>";

    }

    if ($proceso["formato"] === "libro") {
      echo "<br>PRoceso json:<br>";
      print_r($proceso);
      // print_r($proceso_json);
      echo "
          <div class=\"page-header\">
              <h3>
                  ".$proceso_json["id"]." ".$proceso_json["proceso"]."
              </h3>
          </div>";
      echo "<div class=\"list-group\">
              <li class=\"list-group-item\">
                  <h4>Objetivo general</h4>
                  <h5>".$proceso_json["objetivo_general"]."</h5></li>";
      echo "<li class=\"list-group-item\">
              <h4>Objetivos especificos</h4>";
      foreach ($proceso_json["objetivos_especificios"] as $key_obj => $value_obj) {
          echo "<h5>".++$key_obj.": ".$value_obj."</h5>";
      }
      echo "</li>";
      echo "<li class=\"list-group-item\">
              <h4>Actividades<h4>";
      foreach ($proceso_json["actividades"] as $key_act => $value_act) {
          //value_act es el array que tiene descr y metodos
          foreach ($value_act as $key_desc_met => $value_desc_met) {
              if ($key_desc_met === "descripcion") {
                  echo "<h5>".($key_act + 1).": ".$value_desc_met."</h5>";
              }
              if ($key_desc_met === "metodos") {
                  echo "Metodos de la actividad:";
                  foreach ($value_desc_met as $key_metodo => $value_metodo) {
                      echo "<p>".$value_metodo."</p>";
                  }
              }
          }
      }
      echo "</li>";
      echo "<li class=\"list-group-item\">
              <h4>Roles</h4>";
      foreach($proceso_json["roles"] as $key_rol => $value_rol) {
          foreach ($value_rol as $key_key_value_rol => $value_value_rol) {
              if ($key_key_value_rol === "rol") {
                  echo "<p>Rol:".$value_value_rol."<p>";
              }
              if ($key_key_value_rol === "rol_nombre") {
                  echo "<p>Encargado:".$value_value_rol."<p>";
              }
          }
      }
      echo "</li>";
      echo "<li class=\"list-group-item\">
              <h4>Productos</h4>";
      foreach ($proceso_json["productos"] as $key_prod => $value_prod) {
          echo "<h5>".$value_prod."</h5>";
      }
      echo "</li>";
      echo "</div>";
    }

    echo "<br>";
    // print_r($proceso_json);
    // echo $_GET["nombre"];
    echo "</div>";
    echo "</body>";
    echo "</html>";
?>
