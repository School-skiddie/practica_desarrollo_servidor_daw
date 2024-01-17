<?php
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar</title>
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
        if(isset($_POST["seleccionar"])) 
        {
            echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'>Ha sido seleccionada correctamente</div>";
        }
        else if(isset($_POST["borrar"])) 
        {
            try 
            {
                if(!empty($_POST["borrar_seleccionado"])) 
                {
                    foreach ($_POST["borrar_seleccionado"] as $value)
                    {
                        $SQLdelete = $odb -> prepare("DELETE FROM `" . $_POST["tabla"] . "` WHERE " . $_POST["clave_primaria"] ."=:". $_POST["clave_primaria"]);
                        $SQLdelete -> execute(array(":". $_POST["clave_primaria"] => $value));
                    }

                    echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'>Ha sido borrado correctamente</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'>No has seleccionado ningún campo</div>";
                }      
            }
            catch (Exception $e) 
            {
                echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
            } 
        }
        ?>
        <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
            <form method="POST">
                <div class="card-header">Borrar</div>
                <div class="card-body">
                    <?php 
                    if(!isset($_POST["seleccionar"])) 
                    {
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
                    <?php 
                    }
                    else
                    {
                        // guardamos los nombres de las columnas para los inserts
                    ?>
                    <table class="table table-light">
                        <thead>
                            <tr>
                                <?php
                                try 
                                {
                                    $SQLSelect_tablas = $odb -> query("SELECT GROUP_CONCAT(column_name) as columnas FROM information_schema.columns WHERE table_name='" . $_POST["tabla"] . "'");
                                    $tabla = $SQLSelect_tablas -> fetch(PDO::FETCH_ASSOC);

                                    echo "<th scope='col'>Seleccionar</th>";

                                    foreach (explode(",", $tabla["columnas"]) as $value) // hacemos uso del explode, ya que la información la coge por agrupacion
                                    {
                                        echo "<th scope='col'>" . $value . "</th>";
                                    }

                                    $longitud_tabla = sizeof(explode(",", $tabla["columnas"])); // longitud de la tabla de la agrupacion de columnas para luego despues utilizarla
                                }
                                catch (Exception $e) 
                                {
                                    echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                try 
                                {
                                    $SQLSelect_tablas = $odb -> query("SELECT * FROM " . $_POST["tabla"]);
                                    $pos = 1;

                                    while ($row = $SQLSelect_tablas -> fetch(PDO::FETCH_ASSOC))
                                    {
                                        foreach ($row as $value) // recorremos el array ya que de la otra forma tendriamos que poner columnas
                                        {
                                            if($pos == 1) // si el valor es 1 que empiece un nuevo tr
                                            {
                                                echo "<tr>";
                                                // Cogemos el primer valor "casi siempre es la primary key"
                                                echo "<th><input type='checkbox' value='" . $value . "' name='borrar_seleccionado[]'></th>";
                                            }

                                            echo "<th scope='col'>" . $value; // insertamos el valor de la columna especifica

                                            if($pos == $longitud_tabla) // Cerrar el tr cuando se haya alcanzado el limite de columnas y reset del pos
                                            {
                                                echo "</tr>";
                                                $pos = 0;
                                            }

                                            $pos++;
                                        }
                                    }
                                }
                                catch (Exception $e) 
                                {
                                    echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <!-- Guardamos la tabla seleccionada del post anterior -->
                    <input type="hidden" value="<?php echo $_POST["tabla"]; ?>" name="tabla">
                    <!-- Guardamos la clave primaria para saber cual es el primer valor -->
                    <input type="hidden" value="<?php echo explode(",", $tabla["columnas"])[0]; ?>" name="clave_primaria">
                    <?php
                    }
                    ?>
                </div>
                <div class="card-footer">
                    <?php 
                    if(!isset($_POST["seleccionar"])) {
                    ?>
                    <button type="submit" class="btn btn-info text-white" name="seleccionar"><i class="bi bi-send"></i> Seleccionar</button>
                    <?php
                    }
                    else
                    {
                    ?>
                    <button type="submit" class="btn btn-danger" name="borrar"><i class="bi bi-trash3-fill"></i> Borrar</button>
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