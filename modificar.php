<?php
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        footer {
            flex: 0 0 50px;
            /*or just height:50px;*/
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?php
    include "side.php";
    ?>
    <div class="container h-100">
        <?php
        // Contamos cuantos valores hay insertados
        function tuplas($odb)
        {
            $SQLSelect_contar_tuplas = $odb->query("SELECT count(*) as `cuantas_tuplas` FROM " . $_POST["tabla"]);
            $row = $SQLSelect_contar_tuplas->fetch(PDO::FETCH_ASSOC);

            return $row["cuantas_tuplas"];
        }
        $columnas = array();
        if (isset($_POST["seleccionar"])) {
            echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'>Ha sido seleccionada correctamente</div>";
        } else if (isset($_POST["modificar"])) {
            try {
                $preparar_columnas_values = ":" . str_replace(",", ", :",  $_POST["columnas"]); // obtengo los valores y los transformo para preparar la consulta

                $columnas_inputs = array();

                $preparar_columnas_values_modificar = "";

                for ($i = 1; $i <= tuplas($odb); $i++) 
                {
                    foreach (explode(",", $_POST["columnas"]) as $value) 
                    {
                        $columnas_inputs[":" . $value] = $_POST[$value . "_" . $i]; // inserto todos los valores a un array

                        if ($value != $_POST["clave_primaria"]) // prevenimos estar cambiando la clave primaria, solo se usa en el WHERE
                        {
                            $preparar_columnas_values_modificar .= $value . "=:" . $value . ","; // montamos los SET values
                        }
                    }

                    $SQLupdate = $odb->prepare("UPDATE `" . $_POST["tabla"] . "` SET " . substr($preparar_columnas_values_modificar, 0, strlen($preparar_columnas_values_modificar) - 1) . " WHERE " . $_POST["clave_primaria"] . "=:" . $_POST["clave_primaria"]);
                    $SQLupdate->execute($columnas_inputs);

                    $preparar_columnas_values_modificar = ""; // reseteamos el formato

                }

                echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'>Ha sido actualizado correctamente</div>";
            } catch (Exception $e) {
                echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
            }
        }
        ?>
        <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
            <form method="POST">
                <div class="card-header">Modificar</div>
                <div class="card-body">
                    <?php
                    if (!isset($_POST["seleccionar"])) {
                    ?>
                        <div class="form-group">
                            <label for="tabla">Selecciona la tabla</label>
                            <select class="form-control" name="tabla" id="tabla">
                                <?php
                                $SQL = $odb->query("SHOW TABLES");

                                while ($tablas = $SQL->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <option value="<?php echo $tablas["Tables_in_" . DB_NAME]; ?>"><?php echo $tablas["Tables_in_" . DB_NAME]; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                    } else {
                        // guardamos los nombres de las columnas para los inserts
                    ?>
                        <table class="table table-light">
                            <thead>
                                <tr>
                                    <?php
                                    try {
                                        $SQLSelect_columnas = $odb->query("SELECT GROUP_CONCAT(column_name) as `columnas` FROM information_schema.columns WHERE table_name='" . $_POST["tabla"] . "'");
                                        $tabla = $SQLSelect_columnas->fetch(PDO::FETCH_ASSOC);

                                        foreach (explode(",", $tabla["columnas"]) as $value) // hacemos uso del explode, ya que la información la coge por agrupacion
                                        {
                                            echo "<th scope='col'>" . $value . "</th>";
                                        }

                                        $longitud_tabla = sizeof(explode(",", $tabla["columnas"])); // longitud de la tabla de la agrupacion de columnas para luego despues utilizarla
                                    } catch (Exception $e) {
                                        echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $SQLSelect_tablas = $odb->query("SELECT * FROM " . $_POST["tabla"]);

                                    $SQLSelect_columnas = $odb->query("SELECT GROUP_CONCAT(column_name) as `columnas`, GROUP_CONCAT(DATA_TYPE) as `tipo` FROM information_schema.columns WHERE table_name='" . $_POST["tabla"] . "'");
                                    $tabla = $SQLSelect_columnas->fetch(PDO::FETCH_ASSOC);

                                    $pos = 1;

                                    $id = 1; // creamos unas ID ficticias para los campos input

                                    while ($row = $SQLSelect_tablas->fetch(PDO::FETCH_ASSOC)) 
                                    {
                                        foreach ($row as $key => $value) // recorremos el array ya que de la otra forma tendriamos que poner columnas
                                        {
                                            if ($pos == 1) // si el valor es 1 que empiece un nuevo tr
                                            {
                                                echo "<tr>";
                                                // esta es la clave primaria, le ponemos disabled para que no puedan modificarlo
                                                echo "<th scope='col'><input type='" . explode(",", str_replace(
                                                    array("varchar","int"),
                                                    array("text", "number"),
                                                    $tabla["tipo"]
                                                ))[$pos - 1] . "' class='form-control' name='" . explode(",", $tabla["columnas"])[0] . "_" . $id . "' value='" . $value . "' readonly></th>"; // insertamos el valor de la columna especifica
                                            }

                                            if ($pos > 1) // que empiece por el segundo valor, ya que la primary key no se puede modificar
                                            {
                                                echo "<th scope='col'><input type='" . explode(",", str_replace(
                                                    array("varchar","int"),
                                                    array("text", "number"),
                                                    $tabla["tipo"]
                                                ))[$pos - 1] . "' class='form-control' name='" . explode(",", $tabla["columnas"])[$pos - 1] . "_" . $id . "' value='" . $value . "' required></th>"; // insertamos el valor de la columna especifica
                                            }

                                            if ($pos == $longitud_tabla) // Cerrar el tr cuando se haya alcanzado el limite de columnas y reset del pos
                                            {
                                                echo "</tr>";
                                                $pos = 0;
                                                $id++;
                                            }
                                            $pos++;
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- Guardamos la tabla seleccionada del post anterior -->
                        <input type="hidden" value="<?php echo $_POST["tabla"]; ?>" name="tabla">
                        <!-- Guardar todos las columnas -->
                        <input type="hidden" value="<?php echo $tabla["columnas"]; ?>" name="columnas">
                        <!-- Guardamos la clave primaria para saber cual es el primer valor -->
                        <input type="hidden" value="<?php echo explode(",", $tabla["columnas"])[0]; ?>" name="clave_primaria">
                    <?php
                    }
                    ?>
                </div>
                <div class="card-footer">
                    <?php
                    if (!isset($_POST["seleccionar"])) {
                    ?>
                        <button type="submit" class="btn btn-info text-white" name="seleccionar"><i class="bi bi-send"></i> Seleccionar</button>
                    <?php
                    } else {
                    ?>
                        <button type="submit" class="btn btn-info text-white" name="modificar"><i class="bi bi-arrow-clockwise"></i> Modificar</button>
                    <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>

</html>