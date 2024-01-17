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
        if(isset($_POST["borrar"])) 
        {
            try 
            {
                $SQLdelete = $odb -> prepare("DROP TABLE :tabla");
                $SQLdelete -> execute(array(":tabla" => $_POST["tabla"]));

                echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'>La tabla ha sido borrada correctamente</div>";   
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
                    <button type="submit" class="btn btn-danger" name="borrar"><i class="bi bi-trash3-fill"></i> Borrar</button>
                </div>
            </form>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>