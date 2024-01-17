<?php
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar</title>
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
<div class="wrapper">
    <?php
    include "side.php";
    ?>
    <div class="container h-100">
        <?php
        if (isset($_POST["consultar"])) {
            echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'> Consulta realizada </div>";
        }
        ?>
        <div class="card" style="margin-top: 30px;">
            <form method="POST">
                <div class="card-header">Consultar</div>
                <div class="card-body">
                    <?php
                    if (isset($_POST["consultar"])) {
                    ?>
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <?php
                                    try {
                                        $SQLSelect_tablas = $odb->query("SELECT GROUP_CONCAT(column_name) as `columnas` FROM information_schema.columns WHERE table_name='" . $_POST["tabla"] . "'");
                                        $tabla = $SQLSelect_tablas->fetch(PDO::FETCH_ASSOC);

                                        foreach (explode(",", $tabla["columnas"]) as $value) // hacemos uso del explode, ya que la información la coge por agrupacion
                                        {
                                            echo "<th scope='col'>" . $value . "</th>";
                                        }

                                        $longitud_tabla = sizeof(explode(",", $tabla["columnas"])); // longitud de la tabla de la agrupacion de columnas para luego despues utilizarla
                                    } catch (Exception $e) {
                                        echo "No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage();
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
                            </tbody>
                        </table>
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <label for="tabla">Selecciona la tabla</label>
                        <select class="form-control" name="tabla" id="tabla">
                            <?php
                            $SQL = $odb -> query("SHOW TABLES");

                            while ($tablas = $SQL -> fetch(PDO::FETCH_ASSOC))
                            {
                            ?>
                            <option value="<?php echo $tablas["Tables_in_" . DB_NAME]; ?>"><?php echo $tablas["Tables_in_" . DB_NAME]; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success" name="consultar"><i class="bi bi-send"></i> Consultar</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <?php include "footer.php"; ?>
</body>

</html>