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
        if (isset($_POST["ejecutar"])) {
            try {
                if (empty($_POST["opcion"])) {
                    echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'>No has seleccionado nada...</div>";
                } else {
                    switch ($_POST["opcion"]) {
                        case "1":
                            $SQL = $odb->prepare("SELECT * FROM `s` WHERE `edad`=20 AND SUBSTR(nombre, 1, 1)='A'");
                            $SQL->execute();

                            $resultados = $SQL->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($resultados)) {
                                echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'> <pre>" . json_encode($resultados, JSON_PRETTY_PRINT) . "</pre></div>";
                            } else {
                                echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'> Consulta realizada </div>";
                            }
                            break;
                        case "2":
                            $SQL = $odb->prepare("SELECT * FROM `s` WHERE ciudad='Madrid' OR ciudad='Málaga' OR ciudad='Jaén' OR ciudad='Granada'");
                            $SQL->execute();

                            $resultados = $SQL->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($resultados)) {
                                echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'> <pre>" . json_encode($resultados, JSON_PRETTY_PRINT) . "</pre></div>";
                            } else {
                                echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'> Consulta realizada </div>";
                            }
                            break;
                        case "3":
                            $SQL = $odb->prepare("SELECT * FROM `s` WHERE status=NULL");
                            $SQL->execute();

                            $resultados = $SQL->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($resultados)) {
                                echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'> <pre>" . json_encode($resultados, JSON_PRETTY_PRINT) . "</pre></div>";
                            } else {
                                echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'> Consulta realizada </div>";
                            }
                            break;
                        case "4":
                            $SQL = $odb->prepare("SELECT * FROM `s` WHERE edad<18");
                            $SQL->execute();

                            $resultados = $SQL->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($resultados)) {
                                echo "<div class='alert alert-info' style='margin-top: 30px;' role='alert'> <pre>" . json_encode($resultados, JSON_PRETTY_PRINT) . "</pre></div>";
                            } else {
                                echo "<div class='alert alert-success' style='margin-top: 30px;' role='alert'> Consulta realizada </div>";
                            }
                            break;
                    }
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger' style='margin-top: 30px;' role='alert'> No se ha podido obtener la información de la tabla debido a un error de " . $e->getMessage() . "</div>";
            }
        }
        ?>
        <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
            <form method="POST">
                <div class="card-header">Consultas Predefinidas</div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><input type="radio" name="opcion" value="1"></td>
                                <td>Muestra todos los proveedores de 20 años y cuyo nombre contenga la letra 'A' al principio</td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="opcion" value="2"></td>
                                <td>Mostrar todos los proveedores que sean de Almería, Granada, Málaga o Jaén</td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="opcion" value="3"></td>
                                <td>Mostrar los proveedores que no tengan status asignado y sean de Málaga</td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="opcion" value="4"></td>
                                <td>Mostrar el/los proveedores con menor edad.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success" name="ejecutar"><i class="bi bi-send"></i> Ejecutar</button>
                </div>
            </form>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>

</html>