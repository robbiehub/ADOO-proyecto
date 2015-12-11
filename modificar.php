<?php
  include("conexion.php");
  include("mod_iso_12207.php");
  include("mod_iso_29110.php");
  include("mod_libro.php");
  include("jsonificador.php");
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

  if ($proceso["formato"] === "iso_12207") {
    $proceso_json = dame_proceso_json($proceso);
    recibe_json_iso12207($proceso_json);
  }
  if ($proceso["formato"] === "iso_29110") {
    $proceso_json = dame_proceso_json($proceso);
    recibe_json_iso29110($proceso_json);
  }
  if ($proceso["formato"] === "libro") {
    $proceso_json = dame_proceso_json($proceso);
    recibe_json_libro($proceso_json);
  }
?>
