<?php
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejecutar SQL</title>
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
    <div class="container">
        <?php
        if (isset($_POST["ejecutar"])) {
            try {
                $SQL = $odb->prepare($_POST["SQL"]);
                $SQL->execute();


                $resultados = $SQL->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($resultados)) {
                    echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'> <pre>" . json_encode($resultados, JSON_PRETTY_PRINT) . "</pre></div>";
                } else {
                    echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'> Consulta realizada </div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
            }
        }
        ?>
        <form method="POST">
            <div class="card" style="margin-top: 30px;">
                <div class="card-body">
                    <div class="form-group">
                        <label for="sql">SQL</label>
                        <textarea class="form-control" id="sql" name="SQL" placeholder="..." rows="10" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success" name="ejecutar"><i class="bi bi-send"></i> Ejecutar</button>
                </div>
            </div>
        </form>
    </div>
    <?php include "footer.php"; ?>
</body>

</html>