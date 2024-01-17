<?php
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar</title>
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
        $columnas = array();
        if (isset($_POST["seleccionar"])) {
            echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'>Ha sido seleccionada correctamente</div>";
        } 
        else if (isset($_POST["insertar"])) 
        {
            try {

                $preparar_columnas_values = ":" . str_replace(",", ", :",  $_POST["columnas"]); // obtengo los valores y los transformo para preparar la consulta

                $preparar_valores = array(); // hago un array

                foreach (explode(",", $_POST["columnas"]) as $value) 
                {
                    $preparar_valores[":" . $value] = $_POST[$value]; // inserto todos los valores a un array
                }

                $SQLinsert = $odb->prepare("INSERT INTO `" . $_POST["tabla"] . "` VALUES (" . $preparar_columnas_values . ")");
                $SQLinsert->execute($preparar_valores);

                echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'>Ha sido insertada correctamente</div>";
            } catch (Exception $e) {
                echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
            }
        }
        if (isset($_POST["seleccionar"])) {
        ?>
            <div class="card" style="margin-top: 30px;">
                <form method="POST">
                    <div class="card-header">Tabla seleccionada</div>
                    <div class="card-body">
                        <table class="table table-light">
                            <thead>
                                <tr>
                                    <?php
                                    try 
                                    {
                                        $SQLSelect_tablas = $odb->query("SELECT GROUP_CONCAT(column_name) as `columnas` FROM information_schema.columns WHERE table_name='" . $_POST["tabla"] . "'");
                                        $tabla = $SQLSelect_tablas->fetch(PDO::FETCH_ASSOC);

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
                                    $pos = 1;

                                    while ($row = $SQLSelect_tablas->fetch(PDO::FETCH_ASSOC)) {
                                        foreach ($row as $value) // recorremos el array ya que de la otra forma tendriamos que poner columnas
                                        {
                                            if ($pos == 1) // si el valor es 1 que empiece un nuevo tr
                                            {
                                                echo "<tr>";
                                            }

                                            echo "<th scope='col'>" . $value . "</th>"; // insertamos el valor de la columna especifica

                                            if ($pos == $longitud_tabla) // Cerrar el tr cuando se haya alcanzado el limite de columnas y reset del pos
                                            {
                                                echo "</tr>";
                                                $pos = 0;
                                            }

                                            $pos++;
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
                                }
                                ?>
                                <?php
                                try {
                                    $SQLSelect_tablas = $odb->query("SELECT GROUP_CONCAT(column_name) as `columnas`, GROUP_CONCAT(DATA_TYPE) as `tipo` FROM information_schema.columns WHERE table_name='" . $_POST["tabla"] . "'");
                                    $tabla = $SQLSelect_tablas->fetch(PDO::FETCH_ASSOC);
                                    

                                    echo "<tr>";

                                    foreach (explode(",", $tabla["columnas"]) as $key => $value) // hacemos uso del explode, ya que la información la coge por agrupacion
                                    {
                                        echo "
                                            <th>
                                                <input type='". explode(",", str_replace(
                                                    array("varchar","int"),
                                                    array("text", "number"),
                                                    $tabla["tipo"]
                                                ))[$key] ."' class='form-control' id='" . $value . "' name='" . $value . "' placeholder='" . $value . "' required>
                                            </th>";
                                    }

                                    echo "</tr>";

                                    $longitud_tabla = sizeof(explode(",", $tabla["columnas"])); // longitud de la tabla de la agrupacion de columnas para luego despues utilizarla
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- guardamos los nombres de las columnas para los inserts -->
                        <input type="hidden" value="<?php echo $tabla["columnas"]; ?>" name="columnas">
                        <!-- Guardamos la tabla seleccionada del post anterior -->
                        <input type="hidden" value="<?php echo $_POST["tabla"]; ?>" name="tabla">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success" name="insertar"><i class="bi bi-send"></i> Insertar</button>
                    </div>
                </form>
            </div>
        <?php
        } else {
        ?>
            <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
                <form method="POST">
                    <div class="card-header">Insertar</div>
                    <div class="card-body">
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
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info text-white" name="seleccionar"><i class="bi bi-send"></i> Seleccionar</button>
                    </div>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
    <?php include "footer.php"; ?>
</body>

</html>